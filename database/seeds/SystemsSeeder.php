<?php

use Illuminate\Database\Seeder;
use App\Models\System;
use App\Models\User;

class SystemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        System::create([
            'name' => 'Bảng điều khiển',
            'route' => 'admin.dashboard',
            'type' =>  \App\Enums\SystemsModuleType::DASHBOARD,
            'parent_id'=> 0,
            'icon' => 'pe-7s-home',
            'sort' => 1
        ]);

        System::create([
            'name' => 'Tài khoản',
            'route' => 'admin.users.index',
            'type' =>  \App\Enums\SystemsModuleType::USER,
            'parent_id'=> 0,
            'icon' => 'pe-7s-users',
            'sort' => 7
        ]);

        System::create([
            'name' => 'Blog',
            'type' =>  \App\Enums\SystemsModuleType::POST,
            'parent_id'=> 0,
            'position' => 1,
            'icon' => 'pe-7s-news-paper',
            'sort' => 3
        ]);
        $systems = System::whereType('POST')->first();
        System::create([
            'name' => 'Thêm bài viết',
            'route' => 'admin.posts.create',
            'type' =>  \App\Enums\SystemsModuleType::ADD_POST,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Danh sách bài viết',
            'route' => 'admin.posts.index',
            'type' =>  \App\Enums\SystemsModuleType::POST,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Danh mục bài viết',
            'route' => 'admin.posts.categories.index',
            'type' =>  \App\Enums\SystemsModuleType::POST_CATEGORY,
            'parent_id'=> $systems->id,
        ]);

        System::create([
            'name' => 'Sản phẩm',
            'type' =>  \App\Enums\SystemsModuleType::PRODUCT,
            'parent_id'=> 0,
            'position' => 1,
            'icon' => 'pe-7s-plugin',
            'sort' => 6
        ]);
        $systems = System::whereType('PRODUCT')->first();
        System::create([
            'name' => 'Thêm sản phẩm',
            'route' => 'admin.products.create',
            'type' =>  \App\Enums\SystemsModuleType::ADD_PRODUCT,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Danh sách sản phẩm',
            'route' => 'admin.products.index',
            'type' =>  \App\Enums\SystemsModuleType::LIST_PRODUCT,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Danh mục sản phẩm',
            'route' => 'admin.products.categories.index',
            'type' =>  \App\Enums\SystemsModuleType::PRODUCT_CATEGORY,
            'parent_id'=> $systems->id,
        ]);

        System::create([
            'name' => 'Danh sách thuộc tính',
            'route' => 'admin.attributes.index',
            'type' =>  \App\Enums\SystemsModuleType::ATTRIBUTE,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Danh mục thuộc tính',
            'route' => 'admin.attributes.categories.index',
            'type' =>  \App\Enums\SystemsModuleType::ATTRIBUTE_CATEGORY,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Đơn hàng',
            'route' => 'admin.orders.index',
            'type' =>  \App\Enums\SystemsModuleType::HISTORY_IMPORT,
            'parent_id'=> 0,
            'position' => 2,
            'icon' => 'pe-7s-cart',
            'sort' => 3
        ]);
        System::create([
            'name' => 'Pages',
            'type' =>  \App\Enums\SystemsModuleType::PAGE,
            'parent_id'=> 0,
            'position' => 1,
            'icon' => 'pe-7s-wallet',
            'sort' => 5
        ]);
        $systems = System::whereType('PAGE')->first();
        System::create([
            'name' => 'Thêm mới',
            'route' => 'admin.posts.pages.create',
            'type' =>  \App\Enums\SystemsModuleType::ADD_PAGES,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Danh sách bài viết',
            'route' => 'admin.posts.pages.index',
            'type' =>  \App\Enums\SystemsModuleType::LIST_PAGES,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Media',
            'type' =>  \App\Enums\SystemsModuleType::PHOTO,
            'parent_id'=> 0,
            'icon' => 'pe-7s-musiclist',
            'sort' => 7
        ]);
        $systems = System::whereType('PHOTO')->first();
        System::create([
            'name' => 'Thư viện hình ảnh',
            'route' => 'admin.photos.index',
            'type' =>  \App\Enums\SystemsModuleType::PHOTO,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Thư viện video',
            'route' => 'admin.products.videos.index',
            'type' =>  \App\Enums\SystemsModuleType::VIDEO,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Gallery',
            'route' => 'admin.products.galleries.index',
            'type' =>  \App\Enums\SystemsModuleType::GALLERY,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Quản lý menu',
            'route' => 'admin.menus.index',
            'type' =>  \App\Enums\SystemsModuleType::MENU,
            'parent_id'=> 0,
            'icon' => 'pe-7s-menu',
            'sort' => 8
        ]);
        System::create([
            'name' => 'Khách hàng',
            'type' =>  \App\Enums\SystemsModuleType::CUSTOMER,
            'parent_id'=> 0,
            'icon' => 'pe-7s-micro',
            'sort' => 9
        ]);
        $systems = System::whereType('CUSTOMER')->first();
        System::create([
            'name' => 'Ý kiến khách hàng',
            'route' => 'admin.supports.customers.index',
            'type' =>  \App\Enums\SystemsModuleType::CUSTOMER_COMMENT,
            'parent_id'=> $systems->id,
        ]);

        System::create([
            'name' => 'Đội ngũ hỗ trợ',
            'route' => 'admin.supports.index',
            'type' =>   \App\Enums\SystemsModuleType::SUPPORT,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Tin nhắn',
            'route' => 'admin.contacts.index',
            'type' =>   \App\Enums\SystemsModuleType::CONTACT,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Website',
            'type' =>  \App\Enums\SystemsModuleType::WEBSITE,
            'position' => 3,
            'parent_id'=> 0,
            'icon' => 'pe-7s-global',
            'sort' => 10
        ]);
        $systems = System::whereType('WEBSITE')->first();
        System::create([
            'name' => 'Cấu hình hệ thống',
            'route' => 'admin.settings',
            'type' =>   \App\Enums\SystemsModuleType::SETTING,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Đường dẫn',
            'route' => 'admin.alias.index',
            'type' =>   \App\Enums\SystemsModuleType::ALIAS,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Sửa website',
            'route' => 'admin.source.index',
            'type' =>   \App\Enums\SystemsModuleType::SOURCE,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Ngôn ngữ',
            'route' => 'admin.lang.index',
            'type' =>   \App\Enums\SystemsModuleType::LANG,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Config Module',
            'type' =>  \App\Enums\SystemsModuleType::CONFIG_MODULE,
            'position' => 3,
            'parent_id'=> 0,
            'icon' => 'pe-7s-science',
        ]);
        $systems = System::whereType('CONFIG_MODULE')->first();
        System::create([
            'name' => 'Action Modules',
            'route' => 'admin.modules.index',
            'type' =>   \App\Enums\SystemsModuleType::ADD_MODULE,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Modules Systems',
            'route' => 'admin.systems.index',
            'type' =>   \App\Enums\SystemsModuleType::SYSTEMS_MODULE,
            'parent_id'=> $systems->id,
        ]);
        System::create([
            'name' => 'Nhà cung cấp',
            'route' => 'admin.agencys.index',
            'type' =>  \App\Enums\SystemsModuleType::AGENCY,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-id',
            'sort'=> 7,
        ]);
        System::create([
            'name' => 'Nhập hàng',
            'route' => 'admin.imports.create',
            'type' =>  \App\Enums\SystemsModuleType::IMPORT,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-next-2',
            'sort' => 4,
        ]);
        System::create([
            'name' => 'Xuất hàng',
            'route' => 'admin.orders.create',
            'type' =>  \App\Enums\SystemsModuleType::EXPORT,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-back',
            'sort' => 2,
        ]);
        System::create([
            'name' => 'Kho hàng',
            'route' => 'admin.imports.index',
            'type' =>  \App\Enums\SystemsModuleType::HISTORY_IMPORT,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-copy-file',
            'sort' => 5,
        ]);
        System::create([
            'name' => 'Thẻ kho',
            'route' => 'admin.products.stock',
            'type' =>  \App\Enums\SystemsModuleType::STOCK,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-ticket',
            'sort' => 5,
        ]);

//        System::create([
//            'name' => 'Báo cáo',
//            'route' => 'admin.reports.index',
//            'type' =>  \App\Enums\SystemsModuleType::REPORT,
//            'position' => 2,
//            'parent_id'=> 0,
//            'icon' => 'pe-7s-ribbon',
//            'sort' => 7,
//        ]);

        System::create([
            'name' => 'Bình luận',
            'route' => 'admin.comments.index',
            'type' =>  \App\Enums\SystemsModuleType::COMMENTS,
            'icon' => 'pe-7s-comment',
        ]);
        $systems = System::whereType('COMMENTS')->first();
        System::create([
            'name' => 'Bài viết',
            'route' => 'admin.comments.list',
            'var' => 'posts',
            'type' =>  \App\Enums\SystemsModuleType::COMMENTS,
            'icon' => 'pe-7s-comment',
            'parent_id'=> $systems->id,
            'sort' => 1,
        ]);
        System::create([
            'name' => 'Sản phẩm',
            'route' => 'admin.comments.list',
            'var' => 'products',
            'type' =>  \App\Enums\SystemsModuleType::COMMENTS,
            'icon' => 'pe-7s-comment',
            'parent_id'=> $systems->id,
            'sort' => 2,
        ]);

        $systems = System::get()->pluck('id');
        $user = User::whereAccount('admin')->first();
        $user->systems()->attach($systems);
    }
}
