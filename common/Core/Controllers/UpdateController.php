<?php namespace Common\Core\Controllers;

use Artisan;
use Cache;
use Common\Core\BaseController;
use Common\Database\MigrateAndSeed;
use Common\Settings\DotEnvEditor;
use Common\Settings\Setting;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Schema;

class UpdateController extends BaseController {
    /**
     * @var DotEnvEditor
     */
    private $dotEnvEditor;

    /**
     * @var Setting
     */
    private $setting;

    /**
     *s @param DotEnvEditor $dotEnvEditor
     * @param Setting $setting
     */
	public function __construct(DotEnvEditor $dotEnvEditor, Setting $setting)
	{
        $this->setting = $setting;
        $this->dotEnvEditor = $dotEnvEditor;

	    if ( ! config('common.site.disable_update_auth') && version_compare(config('common.site.version'), $this->getAppVersion()) === 0) {
            $this->middleware('isAdmin');
        }
    }

    /**
     * @return Factory|View
     */
    public function show()
    {
        return view('common::update');
    }

    /**
     * @return RedirectResponse
     */
    public function update()
	{
        @ini_set("memory_limit", "-1");
        @set_time_limit(0);

	    //fix "index is too long" issue on MariaDB and older mysql versions
        Schema::defaultStringLength(191);

        app(MigrateAndSeed::class)->execute();

        if (file_exists(base_path('env.example')) && file_exists(base_path('.env'))) {
            $envExampleValues = $this->dotEnvEditor->load(base_path('env.example'));
            $currentEnvValues = $this->dotEnvEditor->load(base_path('.env'));
            $envValuesToWrite = array_diff_key($envExampleValues, $currentEnvValues);
            $envValuesToWrite['app_version'] = $envExampleValues['app_version'];
            $this->dotEnvEditor->write($envValuesToWrite);
        }

        Cache::flush();

        return redirect('/')->with('status', 'Updated the site successfully.');
	}

    /**
     * @return string
     */
    private function getAppVersion()
    {
        try {
            return $this->dotEnvEditor->load(base_path('env.example'))['app_version'];
        } catch (Exception $e) {
            return null;
        }
    }
}
