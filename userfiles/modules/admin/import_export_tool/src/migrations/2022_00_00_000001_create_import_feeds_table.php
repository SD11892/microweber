<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportFeedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('import_feeds')) {
            Schema::create('import_feeds', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->string('source_file')->nullable();
                $table->string('source_file_size')->nullable();
                $table->dateTime('last_downloaded_date')->nullable();
                $table->integer('download_images')->nullable();
                $table->integer('split_to_parts')->nullable();
                $table->string('content_tag')->nullable();
                $table->string('primary_key')->nullable();
                $table->string('update_items')->nullable();
                $table->string('old_content_action')->nullable();
                $table->integer('count_of_contents')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('import_feeds');
    }
}
