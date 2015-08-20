<?php
namespace App\Core;

/**
 * Class View
 *
 * @package App\Core
 *
 * @author Stanislav Dakov st.dakov@gmail.com
 */
class View extends Instance
{
    public function __construct()
    {

    }

    /**
     * @param string $filePath
     * @param array $data
     * @param bool|false $returnHtml
     *
     * @return string
     *
     * @throws \Exception
     */
    public function render($filePath, array $data, $returnHtml = false)
    {
        $viewPath = str_replace('.', '/', $filePath);
        $viewFile = VIEW_DIR . DIRECTORY_SEPARATOR . $viewPath . '.php';

        if (file_exists($viewFile)) {
            ob_start();
            extract($data);

            include($viewFile);

            $html = ob_get_contents();

            ob_end_clean();

        } else {
            throw new \Exception('There is no view file: ' . $viewFile);
        }

        if ($returnHtml) {
            return $html;
        }

        echo $html;
    }
}
