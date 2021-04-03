<?php

use Illuminate\Database\Seeder;

class SystemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\SystemsModule::create([
            'name' => 'Bảng điều khiển',
            'route' => 'admin.dashboard',
            'type' =>  \App\Enums\SystemsModuleType::DASHBOARD,
            'parent_id'=> 0,
            'icon' => 'pe-7s-home',
            'sort' => 1
        ]);

        \App\Models\SystemsModule::create([
            'name' => 'Tài khoản',
            'route' => 'admin.user.index',
            'type' =>  \App\Enums\SystemsModuleType::USER,
            'parent_id'=> 0,
            'icon' => 'pe-7s-users',
            'sort' => 7
        ]);

        \App\Models\SystemsModule::create([
            'name' => 'Blog',
            'type' =>  \App\Enums\SystemsModuleType::POST,
            'parent_id'=> 0,
            'position' => 1,
            'icon' => 'pe-7s-news-paper',
            'sort' => 3
        ]);
        $systems = \App\Models\SystemsModule::whereType('POST')->first();
        \App\Models\SystemsModule::create([
            'name' => 'Thêm bài viết',
            'route' => 'admin.posts.create',
            'type' =>  \App\Enums\SystemsModuleType::ADD_NEWS,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Danh sách bài viết',
            'route' => 'admin.posts.index',
            'type' =>  \App\Enums\SystemsModuleType::POST,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Danh mục bài viết',
            'route' => 'admin.posts.categories.index',
            'type' =>  \App\Enums\SystemsModuleType::POST_CATEGORY,
            'parent_id'=> $systems->id,
        ]);

        \App\Models\SystemsModule::create([
            'name' => 'Sản phẩm',
            'type' =>  \App\Enums\SystemsModuleType::PRODUCT,
            'parent_id'=> 0,
            'position' => 1,
            'icon' => 'pe-7s-plugin',
            'sort' => 6
        ]);
        $systems = \App\Models\SystemsModule::whereType('PRODUCT')->first();
        \App\Models\SystemsModule::create([
            'name' => 'Thêm sản phẩm',
            'route' => 'admin.products.create',
            'type' =>  \App\Enums\SystemsModuleType::ADD_PRODUCT,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Danh sách sản phẩm',
            'route' => 'admin.products.index',
            'type' =>  \App\Enums\SystemsModuleType::LIST_PRODUCT,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Danh mục sản phẩm',
            'route' => 'admin.products.categories.index',
            'type' =>  \App\Enums\SystemsModuleType::PRODUCT_CATEGORY,
            'parent_id'=> $systems->id,
        ]);

        \App\Models\SystemsModule::create([
            'name' => 'Danh sách thuộc tính',
            'route' => 'admin.attributes.index',
            'type' =>  \App\Enums\SystemsModuleType::ATTRIBUTE,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Danh mục thuộc tính',
            'route' => 'admin.attribute_categorys.index',
            'type' =>  \App\Enums\SystemsModuleType::ATTRIBUTE_CATEGORY,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Đơn hàng',
            'route' => 'admin.orders.index',
            'type' =>  \App\Enums\SystemsModuleType::HISTORY_IMPORT,
            'parent_id'=> 0,
            'position' => 2,
            'icon' => 'pe-7s-cart',
            'sort' => 3
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Pages',
            'type' =>  \App\Enums\SystemsModuleType::PAGE,
            'parent_id'=> 0,
            'position' => 1,
            'icon' => 'pe-7s-wallet',
            'sort' => 5
        ]);
        $systems = \App\Models\SystemsModule::whereType('PAGE')->first();
        \App\Models\SystemsModule::create([
            'name' => 'Thêm mới',
            'route' => 'admin.posts.pages.create',
            'type' =>  \App\Enums\SystemsModuleType::ADD_PAGES,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Danh sách bài viết',
            'route' => 'admin.posts.pages.index',
            'type' =>  \App\Enums\SystemsModuleType::LIST_PAGES,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Media',
            'type' =>  \App\Enums\SystemsModuleType::PHOTO,
            'parent_id'=> 0,
            'icon' => 'pe-7s-musiclist',
            'sort' => 7
        ]);
        $systems = \App\Models\SystemsModule::whereType('PHOTO')->first();
        \App\Models\SystemsModule::create([
            'name' => 'Thư viện hình ảnh',
            'route' => 'admin.photos.index',
            'type' =>  \App\Enums\SystemsModuleType::PHOTO,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Thư viện video',
            'route' => 'admin.products.videos.index',
            'type' =>  \App\Enums\SystemsModuleType::VIDEO,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Gallery',
            'route' => 'admin.products.gallerys.index',
            'type' =>  \App\Enums\SystemsModuleType::GALLERY,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Quản lý menu',
            'route' => 'admin.menus.index',
            'type' =>  \App\Enums\SystemsModuleType::MENU,
            'parent_id'=> 0,
            'icon' => 'pe-7s-menu',
            'sort' => 8
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Khách hàng',
            'type' =>  \App\Enums\SystemsModuleType::CUSTOMER,
            'parent_id'=> 0,
            'icon' => 'pe-7s-micro',
            'sort' => 9
        ]);
        $systems = \App\Models\SystemsModule::whereType('CUSTOMER')->first();
        \App\Models\SystemsModule::create([
            'name' => 'Ý kiến khách hàng',
            'route' => 'admin.supports.customers.index',
            'type' =>  \App\Enums\SystemsModuleType::CUSTOMER_COMMENT,
            'parent_id'=> $systems->id,
        ]);

        \App\Models\SystemsModule::create([
            'name' => 'Đội ngũ hỗ trợ',
            'route' => 'admin.supports.index',
            'type' =>   \App\Enums\SystemsModuleType::SUPPORT,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Tin nhắn',
            'route' => 'admin.contact.index',
            'type' =>   \App\Enums\SystemsModuleType::CONTACT,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Website',
            'type' =>  \App\Enums\SystemsModuleType::WEBSITE,
            'position' => 3,
            'parent_id'=> 0,
            'icon' => 'pe-7s-global',
            'sort' => 10
        ]);
        $systems = \App\Models\SystemsModule::whereType('WEBSITE')->first();
        \App\Models\SystemsModule::create([
            'name' => 'Cấu hình hệ thống',
            'route' => 'admin.settings',
            'type' =>   \App\Enums\SystemsModuleType::SITE_SETTING,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Đường dẫn',
            'route' => 'admin.alias.index',
            'type' =>   \App\Enums\SystemsModuleType::ALIAS,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Sửa website',
            'route' => 'admin.source.index',
            'type' =>   \App\Enums\SystemsModuleType::SOURCE,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Ngôn ngữ',
            'route' => 'admin.lang.index',
            'type' =>   \App\Enums\SystemsModuleType::LANG,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Config Module',
            'type' =>  \App\Enums\SystemsModuleType::CONFIG_MODULE,
            'position' => 3,
            'parent_id'=> 0,
            'icon' => 'pe-7s-science',
        ]);
        $systems = \App\Models\SystemsModule::whereType('CONFIG_MODULE')->first();
        \App\Models\SystemsModule::create([
            'name' => 'Action Modules',
            'route' => 'admin.modules.index',
            'type' =>   \App\Enums\SystemsModuleType::ADD_MODULE,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Modules Systems',
            'route' => 'admin.systems.index',
            'type' =>   \App\Enums\SystemsModuleType::SYSTEMS_MODULE,
            'parent_id'=> $systems->id,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Nhà cung cấp',
            'route' => 'admin.agencys.index',
            'type' =>  \App\Enums\SystemsModuleType::AGENCY,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-id',
            'sort'=> 7,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Nhập hàng',
            'route' => 'admin.imports.create',
            'type' =>  \App\Enums\SystemsModuleType::IMPORT,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-next-2',
            'sort' => 4,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Xuất hàng',
            'route' => 'admin.orders.create',
            'type' =>  \App\Enums\SystemsModuleType::EXPORT,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-back',
            'sort' => 2,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Kho hàng',
            'route' => 'admin.imports.index',
            'type' =>  \App\Enums\SystemsModuleType::HISTORY_IMPORT,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-copy-file',
            'sort' => 5,
        ]);
        \App\Models\SystemsModule::create([
            'name' => 'Thẻ kho',
            'route' => 'admin.products.stock',
            'type' =>  \App\Enums\SystemsModuleType::STOCK,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-box2',
            'sort' => 5,
        ]);

        \App\Models\SystemsModule::create([
            'name' => 'Báo cáo',
            'route' => 'admin.reports.index',
            'type' =>  \App\Enums\SystemsModuleType::REPORT,
            'position' => 2,
            'parent_id'=> 0,
            'icon' => 'pe-7s-ribbon',
            'sort' => 7,
        ]);

        \App\Models\SystemsModule::create([
            'name' => 'Bình luận',
            'route' => 'admin.comments.index',
            'type' =>  \App\Enums\SystemsModuleType::COMMENTS,
            'icon' => 'pe-7s-comment',
        ]);

        $systems = \App\Models\SystemsModule::all();
        $user = \App\Models\User::whereAccount('admin')->first();
        foreach($systems as $system):
            \App\Models\UserModuleSystems::create([
                'user_id'  => $user->id,
                'type' => $system->type,
            ]);
        endforeach;
    }
}
