<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PostsType;

class CreatePostType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $postTypea = new PostsType;
        $postTypea -> postTypeName = "پست";
        $postTypea -> postTypeEnName = "posts";
        $postTypea->save();
        $postType = new PostsType;
        $postType -> postTypeName = "اخبار";
        $postType -> postTypeEnName = "news";
        $postType->save();
        $postTypes = new PostsType;
        $postTypes -> postTypeName = "مقالات";
        $postTypes -> postTypeEnName = "article";
        $postTypes->save();



    }
}
