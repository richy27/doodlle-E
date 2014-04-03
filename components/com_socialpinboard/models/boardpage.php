<?php

/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component boardpage model
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

//to get data for design details from database.
class socialpinboardModelboardpage extends SocialpinboardModel {

    function __construct() {
        parent::__construct();
        $this->bId = JRequest::getInt('bId');

        $db = $this->getDBO();
        $query = "SELECT count(board_id) 
                  FROM #__pin_boards 
                  WHERE status=1 
                  AND board_id=" . $this->bId;
        $db->setQuery($query);
        $total = $db->loadResult();
        if (!$this->bId || !$total) {
            $redirect = JRoute::_('index.php?option=com_socialpinboard&view=home', false);
            $app = JFactory::getApplication();
            $app->redirect($redirect, $msg = 'Invalid URL!'); 
        }
    }

    function getPinboard() {
        $db = $this->getDBO();
        $bid = JRequest::getInt('bId');
        if (!$bid & $bid == '0') {
            JError::raiseError(404, implode('<br />', $errors));

            return false;
        }
        $app = JFactory::getApplication();

        $query = "SELECT `board_id`, `user_id`, `board_name`, `board_description`, `board_category_id`, `board_access`, `cuser_id`, `status`, `created_date`, `published`, `updated_date`
                  FROM #__pin_boards 
                  WHERE status=1 
                  AND board_id=" . $bid;
        $db->setQuery($query);
        $pinBoard = $db->loadObjectList();
        return $pinBoard;
    }

    function editBoard() {
        $editBoard = JRequest::getVar('edit_board');
        $bid = JRequest::getInt('bId');
        if (!$bid & $bid == '0') {
            JError::raiseError(404, implode('<br />', $errors));

            return false;
        }
        if (isset($editBoard)) {
            $app = JFactory::getApplication();
            $app->redirect(JRoute::_('index.php?option=com_socialpinboard&view=boardedit&bidd=' . $bid));
        }
    }

    function displayBoards() {
        $db = $this->getDBO();
        $bid = JRequest::getInt('bId');
        if (!$bid & $bid == '0') {
            JError::raiseError(404, implode('<br />', $errors));

            return false;
        }
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $query = "SELECT count(pin_id) 
                  FROM #__pin_pins 
                  WHERE pin_board_id='" . $bid . "' 
                  AND status=1 ";

        $db->setQuery($query);
        $total = $db->loadResult();
        $pageno = 0;
        $length = 20;
        $pins = array();
        if (JRequest::getVar('page')) {
            $pageno = JRequest::getVar('page');
            $pageno = $pageno * 20;
        }
        $colorwhere = '';
        if (JRequest::getVar('color')) {
            $color = JRequest::getVar('color');


            $colorwhere = " AND rgbcolor IN ($color) ";
        }
        $query = "SELECT a.pin_id, a.pin_board_id, a.pin_user_id, a.pin_category_id, a.pin_description, a.pin_type_id, a.pin_url, a.pin_image, a.pin_repin_id, a.pin_real_pin_id, a.pin_repin_count, a.pin_likes_count, a.pin_comments_count, a.pin_views, a.link_type, a.status, a.published, a.gift, a.price, a.rgbcolor, a.created_date, a.updated_date,d.username,d.user_image,d.first_name,d.last_name 
                  FROM #__pin_pins AS a 
                  INNER JOIN #__pin_user_settings d 
                  ON a.pin_user_id=d.user_id 
                  WHERE a.pin_board_id='" . $bid . "' 
                  AND a.status=1 
                  AND d.status=1  $colorwhere
                  ORDER BY a.pin_id 
                  DESC LIMIT $pageno,$length";

        $db->setQuery($query);
        $displaypin = $db->loadObjectList();
        return $displaypin;
    }

    function getUserdetails() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $userId = $user->get('id');
        $query = "SELECT `pin_user_settings_id`, `user_id`, `facebook_profile_id`, `twitter_id`, `first_name`, `last_name`, `email`, `username`, `about`, `location`, `website`, `user_image`, `count`, `status`, `published`, `created_date`, `updated_date`
                  FROM #__pin_user_settings 
                  WHERE user_id=" . $userId;
        $db->setQuery($query);
        $userDetails = $db->loadObjectList();
        return $userDetails;
    }

//Function to get all boards of the user
    function getBoards() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $query = "SELECT board_id,board_name 
                  FROM #__pin_boards 
                  WHERE status = 1 and user_id=$user_id";
        $db->setQuery($query);
        $categories = $db->loadObjectList();

        return $categories;
    }

    //Function to store repins
    function setPins($res) {
        $db = $this->getDBO();
        $repin = $res['repin_id'];
        $query = "SELECT pin_category_id,pin_real_pin_id,pin_repin_count,pin_type_id,pin_url,pin_image,link_type 
                  FROM #__pin_pins 
                  WHERE pin_id=$repin";
        $db->setQuery($query);
        $pins = $db->loadObjectList();
        $pin_type_id = $pins[0]->pin_type_id;
        $pin_url = $pins[0]->pin_url;
        $pin_image = $pins[0]->pin_image;
        $pin_link_type = $pins[0]->link_type;
        $pin_category_id = $pins[0]->pin_category_id;
        $board_id = $res['board_id'];
        $description = $res['description'];
        $repin_count = $pins[0]->pin_repin_count + 1;
        $pin_repin_id = $repin;
        $pin_real_pin_id = $pins[0]->pin_real_pin_id;
        $pin_user_id = $res['pin_user_id'];
        $date = JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        if ($pin_real_pin_id != 0) {
            $this->mailNotification($pin_real_pin_id, $pin_user_id, $mail_type = 1);
        } else {
            $pin_real_pin_id = $repin;
        }
        $query = "UPDATE `#__pin_pins` 
                  SET `pin_repin_count`=$repin_count,`updated_date`='$current_date' 
                  WHERE pin_id=$repin";

        $db->setQuery($query);
        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }
        $query = "INSERT INTO `#__pin_pins` (`pin_id`, `pin_board_id`, `pin_user_id`, `pin_category_id`, `pin_description`, `pin_type_id`, `pin_url`, `pin_image`, `pin_repin_id`, `pin_real_pin_id`, `pin_repin_count`, `pin_likes_count`, `pin_comments_count`, `pin_views`, `status`,`link_type`, `created_date`, `updated_date`) 
                  VALUES ('', $board_id, $pin_user_id, $pin_category_id, '$description', $pin_type_id, '$pin_url', '$pin_image', $pin_repin_id, $pin_real_pin_id, 0, 0, 0, 0, 1,'$pin_link_type', '$current_date', '$current_date')";
        $db->setQuery($query);
        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }
        return $db->insertid();
    }

//Function to get pin information
    function getPin($pinId) {
        $db = $this->getDBO();
        $query = "SELECT pin_type_id,pin_repin_id,pin_real_pin_id,pin_url,pin_board_id,pin_description,pin_image 
                  FROM #__pin_pins 
                  WHERE pin_id=$pinId";

        $db->setQuery($query);
        $pins = $db->loadObjectList();
        return $pins;
    }

    //Function to store likes
    function getLike($pin) {
        $db = JFactory::getDbo();
        $pin_id = $pin['pin_id'];
        $pin_flag = $pin['pin_flag'];
        $user_id = $pin['user_id'];
        $date = JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        if ($pin_flag == 0) {
            $like = 1;
        } else {
            $like = 0;
        }
        $query = "select pin_like from #__pin_likes where pin_id=$pin_id and pin_like_user_id=$user_id";
        $db->setQuery($query);
        $pins = $db->loadObjectList();
        if (!empty($pins)) {

            $query = "UPDATE `#__pin_likes` set `pin_like`=$like where pin_id=$pin_id and pin_like_user_id=$user_id";
        } else {
            $query = "INSERT INTO `#__pin_likes` (`pin_like_id`, `pin_id`, `pin_like_user_id`,`pin_like`, `status`, `created_date`, `updated_date`) VALUES
('', $pin_id, $user_id, $like,1,'$current_date', '$current_date')";
        }

        $db->setQuery($query);

        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }

        $query = "SELECT count(pin_like_id) 
                  AS like_count 
                  FROM #__pin_likes 
                  WHERE pin_id=$pin_id 
                  AND pin_like=1 and status=1";
        $db->setQuery($query);
        $pin_count_result = $db->loadObjectList();
        if (!empty($pin_count_result)) {
            $pin_count = $pin_count_result[0]->like_count;
            $query = "UPDATE `#__pin_pins` set `pin_likes_count`=$pin_count,updated_date='$current_date' where pin_id=$pin_id";
            $db->setQuery($query);

            if (!$db->query()) {
                $this->setError($db->getErrorMsg());
                return false;
            }

            return $pin_count . ' Likes';
        }
    }

    function getBoardUser($boardname) {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $userId = $user->id;
        $boardname = addslashes($boardname);
        $query = "SELECT a.user_id,a.first_name,a.last_name,a.user_image,b.board_name
                  FROM #__pin_user_settings as a 
                  INNER JOIN #__pin_boards as b on a.user_id=b.user_id 
                  WHERE b.board_name='$boardname' 
                  AND a.user_id='$userId' 
                  AND a.status='1' AND b.status='1'";

        $db->setQuery($query);
        $boardUser = $db->loadObject();
        return $boardUser;
    }

//Function to store comments
    function getComment($pin) {
        $db = $this->getDBO();
        $pin_id = $pin['pin_id'];
        $user = JFactory::getUser();
        $user_id = $user->id;
        $comment = $pin['comment'];
        $date = JFactory::getDate();
        $current_date = $date->format("Y-m-d H:i:s");
        $query = "INSERT INTO `#__pin_comments` (`pin_comments_id`, `pin_id`, `pin_user_comment_id`, `pin_comment_text`, `status`, `created_date`, `updated_date`) 
                  VALUES ('', $pin_id, $user_id, '$comment', '1', '$current_date', '$current_date')";
        $db->setQuery($query);

        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }

        $query = "SELECT count(pin_comments_id) as comments_count 
                  FROM #__pin_comments 
                  WHERE pin_id=$pin_id 
                  AND status=1";
        $db->setQuery($query);
        $pin_count_result = $db->loadObjectList();
        $pin_count = $pin_count_result[0]->comments_count;
        $query = "UPDATE `#__pin_pins` 
                  SET `pin_comments_count`=$pin_count,updated_date='$current_date' 
                  WHERE pin_id=$pin_id";
        $db->setQuery($query);

        if (!$db->query()) {
            $this->setError($db->getErrorMsg());
            return false;
        }

        return $comment;
    }

//Function to fetch whether the customer already liked the pin or not
    function getLikes($pin, $user) {
        $db = $this->getDBO();
        $query = "select pin_like from #__pin_likes where pin_id=$pin and status=1 and pin_like_user_id=$user";
        $db->setQuery($query);
        $pin_count_result = $db->loadResult();
        if ($pin_count_result == 1) {
            $pin_count = 1;
        } else {
            $pin_count = 0;
        }

        return $pin_count;
    }

//Function to fetch comments for that pins
    function getComments($pin) {
        $db = $this->getDBO();
        $query = "select a.pin_comments_id,a.pin_user_comment_id,a.pin_comment_text,b.user_image,b.username,b.first_name,b.last_name from #__pin_comments as a inner join #__pin_user_settings as b on a.pin_user_comment_id=b.user_id where a.pin_id=$pin and a.status=1";
        $db->setQuery($query);
        $pin_comment_result = $db->loadObjectList();

        return $pin_comment_result;
    }

//function to fetch current user profile info
    function getUserprofile() {
        $user = JFactory::getUser();
        $user_id = $user->id;
        $db = $this->getDBO();
        $query = "select user_image,username,first_name,last_name from #__pin_user_settings where user_id=$user_id and status=1";
        $db->setQuery($query);
        $user_result = $db->loadObjectList();

        return $user_result;
    }

    //Function to fetch the customer's like info
    function getLikesinformation() {
        $db = $this->getDBO();
        $user = JFactory::getUser();
        $user_id = $user->id;
        $query = "select a.*,b.* from #__pin_pins as a inner join #__pin_likes on a.pin_id=b.pin_id where a.status=1 and b.status=1 and b.pin_like_user_id=$user";
        $db->setQuery($query);
        $pin_count_result = $db->loadObjectList();
        if ($pin_count_result[0]->pin_like == 1) {
            $pin_count = 1;
        } else {
            $pin_count = 0;
        }

        return $pin_count;
    }
function getCurrencySymbol() {
        $db = JFactory::getDBO();
        $query = "SELECT setting_currency from #__pin_site_settings";
            $db->setQuery($query);
            $currency = $db->loadResult();
            return $currency;
}
 function getmobileBoards() {
        $user = JFactory::getUser();
        $memberId = $user->get('id');
        if ($memberId != '0') {
            $db = JFactory::getDBO();
            $query = "select user_id,board_id,board_name,cuser_id from #__pin_boards where status=1 and (user_id=" . $memberId . " OR cuser_id LIKE '%$memberId%')";
            $db->setQuery($query);
            $boardsName = $db->loadObjectList();
            return $boardsName;
        }
    }
}

?>