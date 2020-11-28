<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('configs')) {
            Schema::table('configs', function (Blueprint $table) {

                /** Config Columns */
                $table->integer('amazon_enable')->default(0)->unsigned();
                $table->integer('linkedin_enable')->default(0)->unsigned();
                $table->integer('twitter_enable')->default(0)->unsigned();

                /* Payments Columns */
                $table->integer('payhere_enable')->default(0)->unsigned();
                $table->integer('cashfree_enable')->default(0)->unsigned();
                $table->integer('skrill_enable')->default(0)->unsigned();
                $table->integer('rave_enable')->default(0)->unsigned();
                $table->integer('moli_enable')->default(0)->unsigned();
                $table->integer('omise_enable')->default(0)->unsigned();

            });
        }

        if (Schema::hasTable('addresses')) {
            Schema::table('addresses', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('stores')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (Schema::hasTable('invoice_downloads')) {
            Schema::table('invoice_downloads', function (Blueprint $table) {
                $table->softDeletes();
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
        Schema::table('configs', function (Blueprint $table) {

            $table->dropColumn('amazon_enable');
            $table->dropColumn('linkedin_enable');
            $table->dropColumn('twitter_enable');
            $table->dropColumn('payhere_enable');
            $table->dropColumn('cashfree_enable');
            $table->dropColumn('skrill_enable');
            $table->dropColumn('rave_enable');
            $table->dropColumn('moli_enable');
            $table->dropColumn('omise_enable');

        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('invoice_downloads', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
