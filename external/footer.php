<?php
if (!defined('XHPROF_LIB_ROOT')) {
    define('XHPROF_LIB_ROOT', dirname(dirname(__FILE__)) . '/xhprof_lib');
}

if (extension_loaded('xhprof') && $_xhprof['doprofile'] === true) {
    $profiler_namespace = $_xhprof['namespace']; // namespace for your application
    $xhprof_data = xhprof_disable();
    $xhprof_runs = new XHProfRuns_Default();
    $run_id = $xhprof_runs->save_run($xhprof_data, $profiler_namespace, null, $_xhprof);

    /**
     * check if ajax - do not show a link to profiler output
     */
    $ajax = false;
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
        if ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $ajax = true;
        }
    }

    if ($_xhprof['display'] === true && PHP_SAPI != 'cli' && !$ajax) {
        // url to the XHProf UI libraries (change the host name and path)
        $profiler_url = sprintf($_xhprof['url'] . '/index.php?run=%s&source=%s', $run_id, $profiler_namespace);
        $wt = sprintf(' (%0.3f sec)', floatval($xhprof_data['main()']['wt']) / 1000000);
        echo '<a href="' . $profiler_url . '" target="_blank">Profiler output' . $wt . '</a>';
    }
}
