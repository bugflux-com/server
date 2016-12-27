<?php

use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends BaseSeeder
{

    protected $model = Language::class;

    /**
     * List of the some languages.
     *
     * @var array
     */
    private $languages = [
        'af_ZA' => 'Afrikaans',
        'az_AZ' => 'Azərbaycan dili',
        'id_ID' => 'Bahasa Indonesia',
        'ms_MY' => 'Bahasa Melayu',
        'jv_ID' => 'Basa Jawa',
        'cx_PH' => 'Bisaya',
        'bs_BA' => 'Bosanski',
        'br_FR' => 'Brezhoneg',
        'ca_ES' => 'Català',
        'cs_CZ' => 'Čeština',
        'cy_GB' => 'Cymraeg',
        'da_DK' => 'Dansk',
        'de_DE' => 'Deutsch',
        'et_EE' => 'Eesti',
        'en_PI' => 'English (Pirate)',
        'en_GB' => 'English (UK)',
        'en_UD' => 'English (Upside Down)',
        'en_US' => 'English (US)',
        'es_LA' => 'Español',
        'es_CO' => 'Español (Colombia)',
        'es_ES' => 'Español (España)',
        'eo_EO' => 'Esperanto',
        'eu_ES' => 'Euskara',
        'tl_PH' => 'Filipino',
        'fo_FO' => 'Føroyskt',
        'fr_CA' => 'Français (Canada)',
        'fr_FR' => 'Français (France)',
        'fy_NL' => 'Frysk',
        'ga_IE' => 'Gaeilge',
        'gl_ES' => 'Galego',
        'gn_PY' => 'Guarani',
        'hr_HR' => 'Hrvatski',
        'rw_RW' => 'Ikinyarwanda',
        'is_IS' => 'Íslenska',
        'it_IT' => 'Italiano',
        'sw_KE' => 'Kiswahili',
        'ku_TR' => 'Kurdî (Kurmancî)',
        'lv_LV' => 'Latviešu',
        'fb_LT' => 'Leet Speak',
        'lt_LT' => 'Lietuvių',
        'la_VA' => 'lingua latina',
        'hu_HU' => 'Magyar',
        'nl_NL' => 'Nederlands',
        'nl_BE' => 'Nederlands (België)',
        'nb_NO' => 'Norsk (bokmål)',
        'nn_NO' => 'Norsk (nynorsk)',
        'uz_UZ' => 'O\'zbek',
        'pl_PL' => 'Polski',
        'pt_BR' => 'Português (Brasil)',
        'pt_PT' => 'Português (Portugal)',
        'ro_RO' => 'Română',
        'sq_AL' => 'Shqip',
        'sk_SK' => 'Slovenčina',
        'sl_SI' => 'Slovenščina',
        'fi_FI' => 'Suomi',
        'sv_SE' => 'Svenska',
        'vi_VN' => 'Tiếng Việt',
        'tr_TR' => 'Türkçe',
        'el_GR' => 'Ελληνικά',
        'be_BY' => 'Беларуская',
        'bg_BG' => 'Български',
        'kk_KZ' => 'Қазақша',
        'mk_MK' => 'Македонски',
        'mn_MN' => 'Монгол',
        'ru_RU' => 'Русский',
        'sr_RS' => 'Српски',
        'tg_TJ' => 'Тоҷикӣ',
        'uk_UA' => 'Українська',
        'ka_GE' => 'ქართული',
        'hy_AM' => 'Հայերեն',
        'he_IL' => 'עברית',
        'ur_PK' => 'اردو',
        'ar_AR' => 'العربية',
        'ps_AF' => 'پښتو',
        'fa_IR' => 'فارسی',
        'cb_IQ' => 'کوردیی ناوەندی',
        'ne_NP' => 'नेपाली',
        'mr_IN' => 'मराठी',
        'hi_IN' => 'हिन्दी',
        'as_IN' => 'অসমীয়া',
        'bn_IN' => 'বাংলা',
        'pa_IN' => 'ਪੰਜਾਬੀ',
        'gu_IN' => 'ગુજરાતી',
        'or_IN' => 'ଓଡ଼ିଆ',
        'ta_IN' => 'தமிழ்',
        'te_IN' => 'తెలుగు',
        'kn_IN' => 'ಕನ್ನಡ',
        'ml_IN' => 'മലയാളം',
        'si_LK' => 'සිංහල',
        'th_TH' => 'ภาษาไทย',
        'my_MM' => 'မြန်မာဘာသာ',
        'km_KH' => 'ភាសាខ្មែរ',
        'ko_KR' => '한국어',
        'zh_TW' => '中文(台灣)',
        'zh_CN' => '中文(简体)',
        'zh_HK' => '中文(香港)',
        'ja_JP' => '日本語',
        'ja_KS' => '日本語(関西)',
    ];

    /**
     * Generate random model record.
     *
     * @return array
     */
    protected function always()
    {
        $now = Carbon::now();

        $items = [];
        foreach($this->languages as $code => $name) {
            $items[] = [
                'code' => $code,
                'name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        return $items;
    }
    
}
