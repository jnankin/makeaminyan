<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenuBar
 *
 * @author jnankin
 */
class MenuBar {
    public static $EXTERNAL = array(
        "Login" => array('link' => 'login/index'),
        "Submit a minyan" => array('link' => '@submitMinyan'),
        "About" => array('link' => '@about'),
        "Contact" => array('link' => '@contact')
    );

    public static $CLIENT = array(
        "Dashboard" => array('link' => 'dashboard/index'),
        "My Account" => array('link' => 'account/index'),
        "Submit a minyan" => array('link' => '@submitMinyan'),
        "Logout" => array('link' => 'login/logout')
    );


    private static function writeMenuLink($options, $shortcircuit = false) {
        $tag = '<a href="' . (!isset($options['link']) || $options['link'] === "#" ? "#" : url_for($options['link'])) . '"';

        if (isset($options['onclick']) && !$shortcircuit) {
            $tag .= " onclick='" . addslashes($options['onclick']) . "'";
        }

        $tag .= ">";
        echo $tag;
    }

    private static function hasPermissionsForMenuLink($options){
        if (isset($options['permissions'])){
            $permissions = $options['permissions'];
            if (!is_array($permissions)) $permissions = array($permissions);

            foreach ($permissions as $permission){
                if (is_array($permission)){
                    $andedRet = true;
                    foreach ($permission as $andedPermission){
                        if (!sfContext::getInstance()->getUser()->hasPermission($andedPermission)){
                            $andedRet = false;
                            break;
                        }
                    }

                    if ($andedRet) return true;
                }
                else if (sfContext::getInstance()->getUser()->hasPermission($permission)){
                    return true;
                }

            }
            return false;
        }
        return true;
    }

    public static function writeMenu($menu, $response) {
        $menuSize = count($menu);
        $menuItemCounter = 0;
        ?>
            <div id="menu">
                <ul>
                    <? foreach ($menu as $title => $options):
                        $menuItemCounter++;
                        
                        if (!self::hasPermissionsForMenuLink($options)) continue;
                        ?>
                        <li class="
                            <?=(isset($options['selected']) ? 'current_page_item' : ''); ?>
                            ">

                            <?=self::writeMenuLink($options, $options['selected']); ?>
                                <?=(isset($options['title']) ? $options['title'] : $title); ?>
                                <? if ($options['sublinks']) echo '<span class="down_arrow">&#9660;</span>'; ?>
                            </a>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        <?
    }

}
?>
