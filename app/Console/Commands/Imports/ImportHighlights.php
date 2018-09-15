<?php

namespace App\Console\Commands\Imports;

use App\Models\Category;
use App\Models\Entry;
use App\Models\Image;
use App\Models\Artwork;

use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Console\Commands\AbstractCommand as BaseCommand;

class ImportHighlights extends BaseCommand
{

    protected $signature = 'import:highlights';

    protected $description = 'Imports data from legacy "Highlights" CSVs';

    public function handle()
    {

        // Answer yes if the schema has changed. Otherwise, this is idempotent.
        if (!$this->call('db:reset')) {
            $this->call('migrate');
        }

        $this->import('entity_categories.csv', function ($row) {

            $category = Category::findOrNew($row['id']);
            $category->id = $row['id'];

            $category->category_id = $this->zeroToNull($row['category_id']);
            $category->position = $row['position'];
            $category->total = $row['total'];

            $category->title = $row['title'];
            $category->title_medium = $row['medium'];
            $category->title_short = $row['short'];
            $category->title_abbr = $row['abbr'];

            $category->tn_image_id = $this->zeroToNull($row['tn']);
            $category->tn_entry_id = $row['tn_entry'];

            $category->description = $row['description'];
            $category->description_md = $row['description_md'];

            $category->created_at = $row['created_at'];
            $category->updated_at = $row['updated_at'];

            $category->save();

        });

        $this->import('entity_entries.csv', function ($row) {

            $entry = Entry::findOrNew($row['id']);
            $entry->id = $row['id'];

            $entry->accession = $row['accession'];
            $entry->accession_sort = $row['accession_sort'];

            $entry->title = $row['title'];
            $entry->title_sort = $row['title_sort'];

            $entry->tombstone = $row['tombstone'];
            $entry->tombstone_md = $row['tombstone_md'];

            $entry->description = $row['description'];
            $entry->description_md = $row['description_md'];

            $entry->is_copyrighted = $row['copyrighted'];

            $entry->created_at = $row['created_at'];
            $entry->updated_at = $row['updated_at'];

            $entry->save();

        });

        $this->import('entity_objects.csv', function ($row) {

            $artwork = Artwork::findOrNew($row['id']);
            $artwork->id = $row['id'];

            $artwork->accession = $row['accession'];
            $artwork->accession_sort = $row['accession_sort'];

            $artwork->is_copyrighted = $row['copyrighted'];

            $artwork->created_at = $row['created_at'];
            $artwork->updated_at = $row['updated_at'];

            $artwork->save();

        });

        $this->import('entity_images.csv', function ($row) {

            $image = Image::findOrNew($row['id']);
            $image->id = $row['id'];

            $image->filename = $row['filename'];

            $image->cx = $row['cx'];
            $image->cy = $row['cy'];
            $image->cw = $row['cw'];
            $image->ch = $row['ch'];

            $image->base64_tn = $row['base64_tn'];
            $image->base64_sq = $row['base64_sq'];

            $image->created_at = $row['created_at'];
            $image->updated_at = $row['updated_at'];

            $image->save();

        });

        // Set auto increment to one greater than the max id of each model
        foreach([
            Category::class,
            Entry::class,
            Artwork::class,
            Image::class,
        ] as $model) {

            $this->setAutoIncrement($model);

        }
    }

    private function import($filename, $rowCallback) {

        $path = $this->getCsvPath($filename);

        $csv = Reader::createFromPath( $path, 'r' );
        $csv->setHeaderOffset(0);

        $rows = $csv->getRecords();

        foreach ($rows as $row) {

            // There's no easy way to export `null` unescaped
            foreach ($row as $key => $value) {
                if ($value === '\N') {
                    $row[$key] = null;
                }
            }

            $rowCallback($row);

            $this->info('Imported #' . $row['id'] . ' from ' . $filename);
        }

    }

    private function getCsvPath($filename)
    {
        return Storage::disk('dumps')->getDriver()->getAdapter()->getPathPrefix() . 'highlights/' . $filename;
    }

    private function zeroToNull($value)
    {
        return $value == 0 ? null : $value;
    }

    private function setAutoIncrement($model)
    {
        $table = with(new $model)->getTable();
        $newId = DB::table($table)->max('id') + 1;

        DB::update("ALTER TABLE $table AUTO_INCREMENT = $newId;");
    }

}
