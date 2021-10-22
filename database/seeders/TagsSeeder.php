<?php

namespace Database\Seeders;

use App\Models\BookChapter;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Spatie\Tags\Tag;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=TagsSeeder
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('categories')->truncate();
        DB::table('tags')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $categories = [
            'comic' => '漫画',
            'video.area' => '地区',
            'video.place' => '场景',
            'video.topic' => '主题',
            'video.leaning' => '性癖',
            'video.identity' => '角色',
            'video.wear' => '穿着',
            'video.body' => '身材',
            'video.posture' => '体位',
            'video.player' => '人数',
        ];

        collect($categories)->each(function ($name, $type) {
            Category::create([
                'name' => $name,
                'type' => $type,
                'status' => 1,
            ]);
        });

        $list = [
            'comic' => '日漫 韩漫 精选 汉化 最新 完结 单行本 同人志 恋爱 校园 剧情 都市 强奸 萝莉 乱伦 恐怖 奇幻 多人 后宫 男男 百合 猎奇 写真 CG画集 全彩',
            'video.area' => '日本 韩国 国产 欧美',
            'video.place' => '校园 医院 电车 图书馆 巴士 办公室 美容院 健身房 温泉',
            'video.topic' => '偶像 主观视角 首次亮相 流出 无码 薄马赛克 马赛克破解 企画 共演 总编辑 感谢祭 女性向 成人电影 明星脸 艺人 素人 R15 4K VR 4小时以上 16小时以上 局部特写 写真偶像 3D 动漫改编',
            'video.leaning' => '恋爱 约会 出轨 强奸 乱伦 NTR 痴女 痴汉 偷窥 蕾丝 泡泡浴 野外露出 性转换 女体化 男同性恋 妄想 偷窥 M男 刺青 黑白配 恋物癖 高潮 运动 恋乳癖 恶作剧 运动 奴隶 流汗 性骚扰 情侣 泥醉 处男 触手',
            'video.identity' => '美少女 女子高生 女子大生 妹・姐 若妻 人妻 女教师 秘书 护士 女医 拉拉队 女主播 模特儿 赛车女郎 家教 辣妹 寡妇 空姐 母子 女仆 修女 新娘 大小姐 女王 老板娘 格斗家 检察官・警察',
            'video.wear' => '学生服 制服 运动短裤 眼镜 内衣 水手服 泳装 迷你裙 和服 Cosplay 裸体围裙 女忍者 高跟鞋 靴子 OL 兽耳 短裙 泳装 迷你裙 浴衣 瑜伽服 紧身衣 丝袜 旗袍 兔女郎',
            'video.body' => '熟女 处女 巨乳 萝莉 无毛 美臀 苗条 素人 美乳 美腿 巨根 贫乳・微乳 高挑 孕妇 大屁股 瘦小身型 人妖 肌肉 超乳',
            'video.posture' => '乳交 中出 69 淫语 女上位 骑乘位 自慰 颜射 潮吹 口交 舔阴 肛交 手淫 放尿 足交 按摩 吞精 剃毛 二穴同入 母乳 不穿内裤 不穿胸罩 深喉 失神 接吻 拳交 饮尿 排便 食粪 凌辱 捆绑・紧缚 轮奸 玩具 SM 羞耻 拘束・监禁 调教 插入异物 灌肠 催眠',
            'video.player' => '多P 两女一男 两男一女 两男两女 夫妻交换 外国人 白人 黑人 老人',
        ];

        collect($list)->each(function ($tags, $type) {
            $tags = explode(' ', $tags);

            $insert = collect($tags)->map(function ($tag) use ($type) {
                $locale = App::currentLocale();
                $name = [$locale => $tag];
                return [
                    'name' => json_encode($name),
                    'slug' => json_encode($name),
                    'type' => $type,
                    'suggest' => 1,
                    'queries' => 0,
                    'order_column' => 0,
                ];
            })->toArray();

            Tag::insert($insert);
        });
    }
}
