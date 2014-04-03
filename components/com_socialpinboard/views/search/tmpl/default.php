<?php
/**
 * @name          : Joomla Social Pinboard
 ** @version	  : 2.0
 * @package       : apptha
 * @since         : Joomla 1.5
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2011 Powered by Apptha
 * @license       : http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @abstract      : Social Pinboard Component search view
 * @Creation Date : March 2013
 * @Modified Date : March 2013
 * */
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('components/com_socialpinboard/css/edit-style.css');
$document->addStyleSheet('components/com_socialpinboard/css/facebox.css');
$document->addStyleSheet('components/com_socialpinboard/css/style.css');
$document->addStyleSheet('components/com_socialpinboard/css/reset.css');
$document->addStyleSheet('components/com_socialpinboard/css/pinboard.css');
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
$document->addScript('components/com_socialpinboard/javascript/jquery.ui.core.js');
$document->addScript('components/com_socialpinboard/javascript/chrome.js');
$document->addScript('components/com_socialpinboard/javascript/facebox.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.isotope.min.js');
$document->addScript('components/com_socialpinboard/javascript/scroll/jquery.infinitescroll.min.js');
$app = JFactory::getApplication();
$templateparams = $app->getTemplate(true)->params; // get the tempalte
$sitetitle = $templateparams->get('sitetitle');
if (isset($sitetitle)) {
    JDocument::settitle($sitetitle);
    $document->setDescription($sitetitle);
    $document->setTitle($sitetitle);
} else {

    $config = JFactory::getConfig();
    $sitetitle = $config->get('sitename');
    $document->setDescription($sitetitle);
    $document->setTitle($sitetitle);
}
$document = JFactory::getDocument();
$pins = $this->searches;
$user = JFactory::getUser();
$search = JRequest::getVar('search');
$searchtype = $search;
$serachVal = JRequest::getVar('serachVal');
$inc = '';$fBoardId = '';
$pid = JREQUEST::getInt('pid', 1);
$totalpages = $this->totalPageCount;
$model = $this->getModel();
$boardPins = $this->getBoardPins;
$dispBoard = $model->displayBoard();
$user_res = $model->getUserprofile();
$userDetails = $model->userDetails();
$boardDetails = $model->getBoards();
$baseurl = JURI::base();
$serachVal = isset($serachVal) ? $serachVal : '';
if (isset($serachVal) && !empty($serachVal))
    $serachRes = ' - ' . $serachVal;
else
    $serachRes=$serachVal;
$value = JRequest::getVar('q');

if ($serachVal == '') {
    $searchname = $value;
} else {
    $searchname = $serachVal;
}
$totalBoard = $this->totalBoard;
$getCurrencySymbol =  $this->getCurrencySymbol;
$u_agent = $_SERVER['HTTP_USER_AGENT'];
$ub = '';
if ($totalpages >= ($pid + 1)) {
    $pid = $pid + 1;
}
if ($search == 0) {
    $peopleurl = JRoute::_('index.php?option=com_socialpinboard&view=search&search=2&q=' . $searchname);
    $pinurl = JRoute::_('index.php?option=com_socialpinboard&view=search&search=0&q=' . $searchname);
    $boardurl = JRoute::_('index.php?option=com_socialpinboard&view=search&search=1&q=' . $searchname . '&pid=' . $pid);
} else if ($search == 1) {
    $peopleurl = JRoute::_('index.php?option=com_socialpinboard&view=search&search=2&q=' . $searchname);
    $pinurl = JRoute::_('index.php?option=com_socialpinboard&view=search&search=0&q=' . $searchname . '&pid=' . $pid);
    $boardurl = JRoute::_('index.php?option=com_socialpinboard&view=search&search=1&q=' . $searchname);
} else if ($search == 2) {
    $peopleurl = JRoute::_('index.php?option=com_socialpinboard&view=search&search=2&q=' . $searchname . '&pid=' . $pid);
    $pinurl = JRoute::_('index.php?option=com_socialpinboard&view=search&search=0&q=' . $searchname);
    $boardurl = JRoute::_('index.php?option=com_socialpinboard&view=search&search=1&q=' . $searchname);
}

//Get the follow boards
if (count($dispBoard) != 0) {
    foreach ($dispBoard as $fboards) {

        if ((count($dispBoard) - 1) == $inc) {
            $fBoardId.= $fboards->board_id;
        } else {
            $fBoardId.= $fboards->board_id . ',';
        }
        $inc++;
    }
}
?>



<input type="hidden" name="search" value="<?php echo $search; ?>"/>
<input type="hidden" name="searchval" value="<?php echo $searchname; ?>"/>
<div id="SearchBar" class="clearfix">
    <span>
        <a class="<?php if ($search == 0)
    echo JText::_('active'); ?>" href="<?php echo $pinurl; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_PINS'); ?></a>
    </span>
    <span>
        <a class="<?php if ($search == 1)
               echo JText::_('active'); ?>" href="<?php echo $boardurl; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_BOARDS'); ?></a>
    </span>
    <span >
        <a class="<?php if ($search == 2)
               echo JText::_('active'); ?>" href="<?php echo $peopleurl; ?>"><?php echo JText::_('COM_SOCIALPINBOARD_PEOPPLE'); ?></a>
    </span>
    <span class="search_text">

        <?php
           if ($searchname == 'Search') {
               echo ' -  <span>' . JText::_('COM_SOCIALPINBOARD_NO_RESULTS_FOUND') . '</span>';
           } else if ($searchname == '') {
               echo ' -  <span>' . JText::_('COM_SOCIALPINBOARD_ALL_RESULTS') . '</span>';
           } else {
               echo ' - ' . JText::_('COM_SOCIALPINBOARD_SEARCH_RESULT') . ' <span>' . ucfirst($searchname) . '</span>';
           }
        ?>
       </span>

   </div>
   <div id="container">
    <?php
           if ($search == 1) {

               if (count($pins) != 0) {
    ?>

    <?php foreach ($dispBoard as $board) {
    ?>

                       <div class="pin pin-board">
                           <h3 class="board-title"><a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $board->board_id) ?>"><?php echo $board->board_name; ?> </a><?php
                       if (!empty($board->cuser_id)) {
    ?>
                           <div class="edit-board"></div>
            <?php } ?></h3>
                   <h4 class="user">
                       <a class="colorless" href="/trujillos/">
                <?php
                       if ($board->user_image != '') {
                           $board_user_image = JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($board->user_image);
                       } else {
                           $board_user_image = JURI::Base() . '/components/com_socialpinboard/images/no_user.jpg';
                       }
                ?>

                       <img style="width: 18px;
                            height: 18px;" src="<?php echo $board_user_image; ?>"><?php echo ' '.ucfirst($board->first_name) . ' ' . ucfirst($board->last_name); ?>
                   </a>
               </h4>
               <div class="images pin_no_img">
            <?php
                       $i = 0;
                       foreach ($pins as $pin) {
                           if (!empty($pin[$board->board_id]['pin_image'])) {
                               if ($pin[$board->board_id]['link_type'] == 'youtube' || $pin[$board->board_id]['link_type'] == 'vimeo') {
                                   $imgSrc = $pin[$board->board_id]['pin_image'];
                                   $imgSrc1 = $pin[$board->board_id]['pin_image'];
                               } else {
                                   $imgSrc = $baseurl . 'images/socialpinboard/pin_thumb/' . $pin[$board->board_id]['pin_image'];
                                   $imgSrc1 = $baseurl . 'images/socialpinboard/pin_medium/' . $pin[$board->board_id]['pin_image'];
                               }
                               $i++;
                               if ($i == 1) {
            ?>
                                   <a class="new-img" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $board->board_id) ?>">
                                       <img src="<?php echo $imgSrc1; ?>" alt="" width="190" height="140"/>
                                   </a><?php } else { ?>
                                   <a class="new-img" href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $board->board_id) ?>">
                                       <img src="<?php echo $imgSrc; ?>" alt="" width="60" height="45"/>
                                   </a><?php } ?>
            <?php
                           }
                       }
            ?>
                   </div>

        <?php
                       if ($board->user_id == $user->id && $board->user_id != '') {
        ?>
                           <div class="followBoard">
                               <a href="<?php echo JRoute::_('index.php?options=com_socialpinboard&view=boardedit&bId=' . $board->board_id) ?>" class="Button13 Button WhiteButton"><strong><?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?></strong></a>
                           </div>
        <?php
                       } else if (!empty($user->id)) {
                           $followers = socialpinboardModelSearch::followUserBoard($user->id, $board->user_id);
                           if ($followers['0'] != '') {
                               $follwercount = count($followers);
                           } else {
                               $follwercount = '0';
                           }
        ?>
                           <div id="newfollowboard<?php echo $board->board_id; ?>">
            <?php
                           if ($followers == 'all' || in_array($board->board_id, $followers)) {
            ?>
                               <input type="button" name="unfollowboard" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW_BOARD'); ?>" class="unfollowboard" id="unfollowboard<?php echo $board->board_id; ?>" onclick="unfollows(<?php echo $user->id . ',' . $board->user_id . ',' . $board->board_id; ?>)" />
            <?php
                           } else {
            ?>
                               <input type="button" name="followboard" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW_BOARD'); ?>" class="followboard followboard-height" id="followboard<?php echo $board->board_id; ?>" onclick="followboard(<?php echo $user->id . ',' . $board->user_id . ',' . $board->board_id; ?>)"/>
<?php } ?>
                       </div>
<?php } ?>

                   </div>
<?php } ?>

    <?php
               } else {
    ?>
                   <div id="login_error_msg">

                       <div id="system-message-container">
                           <dl id="system-message">
                               <dd class="error message">
                                   <ul>
                                       <li id="login_error_msg"><?php echo JText::_('COM_SOCIALPINBOARD_SORRY'); ?></li>
                                   </ul>
                               </dd>
                           </dl>
                       </div>
                   </div>
    <?php
               }
           } else if ($search == 0) {
               if (count($pins) != 0) {

                   foreach ($pins as $arrPins) {
                       if ($user->get('id') != 0) {
                           $like_res = $model->getLikes($arrPins->pin_id, $user->id);
                       }
    ?>
                       <div class="pin" id="pin_div_<?php echo $arrPins->pin_id; ?>">
                           <div class="pic  pic_show_functional search_page">
            <?php
                       if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
                           $src_path = $arrPins->pin_image;
                       } else {
                           $src_path = "images/socialpinboard/pin_medium/" . rawurlencode($arrPins->pin_image);
                       }
                       if (preg_match('/MSIE/i', $u_agent)) {
                           if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
            ?>
                               <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink" style="position: absolute;z-index: 1;top: 25%;left: 40%;"><img src="<?php echo JURI::base() . "components/com_socialpinboard/images/play_btn.png"; ?>" width="50" height="50" alt="" class="play_button" /></a>
                <?php //if ($arrPins->gift == '1') {
                ?>
<!--                               <strong class="PriceContainer"><strong class="price">-->
                    <?php //echo $arrPins->price; ?>
<!--                                   </strong></strong>-->
                    <?php// } ?>
<?php } ?>

                       <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink" ><img src="<?php echo $src_path; ?>" alt="" class="PinImageImg" />
                <?php if ($arrPins->gift == '1') { ?> <strong class="PriceContainer"><strong class="price"><?php echo $getCurrencySymbol.$arrPins->price; ?></strong></strong><?php } ?>
                       </a>

            <?php
                       } else {
                           if ($arrPins->link_type == 'youtube' || $arrPins->link_type == 'vimeo') {
            ?>
                               <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink facebox" onclick="popup();" style="position: absolute;z-index: 1;top: 25%;left: 40%;"><img src="<?php echo JURI::base() . "components/com_socialpinboard/images/play_btn.png"; ?>" width="50" height="50" alt="" class="play_button" /></a>
            <?php //if ($arrPins->gift == '1') {
 ?>
<!--                               <strong class="PriceContainer"><strong class="price">-->
                <?php //echo $arrPins->price; ?>
<!--                                   </strong></strong>-->
                <?php //} ?>
<?php } ?>
                           <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" class="PinImage ImgLink facebox" onclick="popup();"><img src="<?php echo $src_path; ?>" alt="" class="PinImageImg"/>
<?php if ($arrPins->gift == '1') { ?> <strong class="PriceContainer"><strong class="price"><?php echo $getCurrencySymbol.$arrPins->price; ?></strong></strong><?php } ?>
                       </a>
            <?php
                       }
            ?>


                       <div class="btns">

                <?php
                       if ($user->get('id') != 0) {
                           if ($like_res == 1) {
                               $likestyle = 'display:none';
                               $unlikestyle = 'display:block';
                           } else {
                               $unlikestyle = 'display:none';
                               $likestyle = 'display:block';
                           }
                           $userid = $user->id;
                       } else {
                           $userid = 0;
                           $likestyle = 'display:block';
                           $unlikestyle = 'display:none';
                       }
                ?>

                       <div class="btn-pinlist btn-repin">
                           <input type="button" onclick="getpin(<?php echo $arrPins->pin_id; ?>,'<?php echo JURI::base(); ?>',<?php echo $userid; ?>)" title="Repin" id="showrepindiv<?php echo $arrPins->pin_id; ?>" class="report_repin" value="<?php echo JText::_('COM_SOCIALPINBOARD_REPIN'); ?>" />
                       </div>
                <?php
                       //if the pinner and logged in user are same,display edit button, else like/unlike button
                       if ($arrPins->pin_user_id != $userid) {
                ?>
                           <div class="btn-pinlist btn-like" id="likebtn<?php echo $arrPins->pin_id; ?>">

                               <input type="button" class="pin_like" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 0; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?>" id="like<?php echo $arrPins->pin_id; ?>" style="<?php echo $likestyle; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_LIKE'); ?>" />
                               <input type="button" class="pin_unlike" onclick="getlike(<?php echo $arrPins->pin_id; ?>,<?php echo $userid; ?>,<?php echo $flag = 1; ?>)" title="<?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?>" id="unlike<?php echo $arrPins->pin_id; ?>" style="<?php echo $unlikestyle; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNLIKE'); ?>" />
                           </div>
                <?php
                       } elseif ($arrPins->pin_user_id == $userid) {
                ?>
                           <div class="btn-pinlist btn-like btn-edit" >
                               <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pinedit&pinId=' . $arrPins->pin_id); ?>" class="pin_edit"   title="<?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?>" id="like<?php echo $arrPins->pin_id; ?>" >
<?php echo JText::_('COM_SOCIALPINBOARD_EDIT'); ?>
                       </a>
                   </div>
                <?php
                       }
                ?>
                       <div class="btn-pinlist btn-comment">
                           <input type="button" onclick="comment(<?php echo $arrPins->pin_id; ?>,<?php echo $user->id; ?>)"  title="Comment" id="comment<?php echo $arrPins->pin_id; ?>" value="<?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?>" />
                       </div>
                   </div>




               </div>

               <div class="description"><?php echo $arrPins->pin_description; ?></div>
               <div class="statistics">

                   <span id="likescountspan<?php echo $arrPins->pin_id; ?>" >
                <?php
                       $like = '';
                       if ($arrPins->pin_likes_count != 0 && $arrPins->pin_likes_count == 1) {
                           $like = $arrPins->pin_likes_count . JText::_('COM_SOCIALPINBOARD_LIKE');
                       } else if ($arrPins->pin_likes_count > 1) {
                           $like = $arrPins->pin_likes_count . JText::_('COM_SOCIALPINBOARD_LIKES');
                       }
                       echo $like;
                ?> </span>


                   <span id="commentscountspan<?php echo $arrPins->pin_id; ?>" ><?php
                       if ($arrPins->pin_comments_count != 0 && $arrPins->pin_comments_count == 1) {
                           echo $arrPins->pin_comments_count . JText::_('COM_SOCIALPINBOARD_COMMENT');
                       } else if ($arrPins->pin_comments_count > 1) {
                           echo $arrPins->pin_comments_count . JText::_('COM_SOCIALPINBOARD_COMMENTS');
                       }
                ?></span>

                   <span id="repincountspan<?php echo $arrPins->pin_id; ?>" ><?php
                       if ($arrPins->pin_repin_count == 1) {
                           echo $arrPins->pin_repin_count . JText::_('COM_SOCIALPINBOARD_REPIN');
                       } else if ($arrPins->pin_repin_count > 1) {
                           echo $arrPins->pin_repin_count . JText::_('COM_SOCIALPINBOARD_REPINS');
                       }
                ?> </span>
                   <i class="loading_grid" id="loading_grid_<?php echo $arrPins->pin_id; ?>" style="display:none;"></i>

               </div>
               <div class="convo attribution clearfix">  <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $arrPins->pin_user_id); ?>">
                <?php
                       if ($arrPins->user_image != '') {
                ?>


                           <img height="30" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($arrPins->user_image); ?>" title="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>"
                           alt="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" width="30" class="ImgLink thumb-img"/>
                     <?php
                       } else {
                     ?>
                           <img src="<?php echo JURI::Base(); ?>/components/com_socialpinboard/images/no_user.jpg" title="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" alt="<?php echo $arrPins->first_name . ' ' . $arrPins->last_name; ?>" height="30" width="30" class="ImgLink thumb-img" />
                <?php
                       }
                ?>       </a>

                   <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $arrPins->pin_user_id); ?>">
<?php echo $arrPins->first_name . " " . $arrPins->last_name; ?>
                   </a> <?php echo JText::_('COM_SOCIALPINBOARD_ONTO'); ?>
                   <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boardpage&bId=' . $arrPins->board_id); ?>">
<?php echo $arrPins->board_name; ?></a>
               </div>
               <div class="comments clearfix" id="commentDiv<?php echo $arrPins->pin_id; ?>">
                   <ul>
                <?php
                       $comment_res = $model->getComments($arrPins->pin_id);


                       if ($comment_res != '') {
                           $i = 0;
                           $count = count($comment_res);
                           $new_flag = 0;
                           foreach ($comment_res as $comment) {
                               if ($i < 6) {
                ?><li>
                                       <div class="comment clearfix ">

                                           <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
                            <?php
                                   if ($comment->user_image == '') {
                            ?>
                                       <img height="30" src="<?php echo JURI::base() . 'components/com_socialpinboard/images/no_user.jpg' ?>" title="<?php echo $comment->username; ?>"
                                       alt="<?php echo $comment->first_name . " " . $comment->last_name; ?>" width="30" class="ImgLink thumb-img"/>
                                 <?php
                                   } else {
                                 ?>
                                  <img height="30" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . rawurlencode($comment->user_image); ?>" title="<?php echo $comment->username; ?>"
                                       alt="<?php echo $comment->first_name . " " . $comment->last_name; ?>" width="30" class="ImgLink thumb-img"/>
                                 <?php
                                   }
                                 ?>
                          </a>
                          <div class="board_grid board_content">
                              <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $comment->pin_user_comment_id); ?>">
<?php echo $comment->first_name . " " . $comment->last_name; ?>
                               </a>
                               <span>
<?php echo stripslashes($comment->pin_comment_text); ?> </span>
                           </div>
                       </div>
                   </li>
                <?php
                               } else {
                                   $new_flag = 1;
                                   break;
                               }
                               $i++;
                           }
                       }
                ?>
                       </ul>
            <?php
                       if ($new_flag == 1) {
            ?>

                           <div class="homecomment">
                               <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=pin&pinid=' . $arrPins->pin_id); ?>" >
                    <span style="color: #999;">View All <span id="commentsspan<?php echo $arrPins->pin_id; ?>" ><?php
                            if ($arrPins->pin_comments_count != 0 && $arrPins->pin_comments_count == 1) {
                                echo $arrPins->pin_comments_count;
                            } else if ($arrPins->pin_comments_count > 1) {
                                echo $arrPins->pin_comments_count;
                            }
            ?></span>  comments </span>

                </a></div>
<?php } ?>
                        <div class="write homecommentwrite" id="writecomment<?php echo $arrPins->pin_id; ?>" style="display:none">

                            <div class="newcomment">

                                <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user->id); ?>" class="ImgLink">
                        <?php
                        if ($user_res[0]->user_image == '') {
                        ?><img height="30" src="<?php echo JURI::base() . 'components/com_socialpinboard/images/no_user.jpg'; ?>" title="<?php echo $user_res[0]->username; ?>"
                             alt="<?php echo $user_res[0]->username; ?>" width="30" />
                             <?php
                         } else {
                             ?>
                        <img height="30" src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $user_res[0]->user_image; ?>" title="<?php echo $user_res[0]->username; ?>"
                             alt="<?php echo $user_res[0]->username; ?>" width="30" />
                             <?php
                         }
                             ?>

                    </a>
                    <?php
                         if ($userid) {
                    ?>

                             <textarea id="commentContent<?php echo $arrPins->pin_id; ?>"  name="content"
                                       onfocus="if (value == '<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>') { value = ''; }"
                                       onblur="if (value == '') { value = '<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>'; }"  maxlength="200"><?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?></textarea>



                             <a href="javascript:void(0)"
                                class="button" onclick="doHomeComment(<?php echo $arrPins->pin_id; ?>,'<?php echo $user_res[0]->first_name; ?>','<?php echo $user_res[0]->last_name; ?>','<?php echo $user_res[0]->user_image; ?>','<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $user->id); ?>')"><?php echo JText::_('COM_SOCIALPINBOARD_COMMENT'); ?></a>
<?php } ?>

                  </div>

              </div>
              <div class="clear"></div>
          </div>
      </div>
    <?php
                     }
                 } else {
    ?>
                     <div id="login_error_msg">

                         <div id="system-message-container">
                             <dl id="system-message">
                                 <dd class="error message">
                                     <ul>
                                         <li id="login_error_msg"><?php echo JText::_('COM_SOCIALPINBOARD_SORRY_PINS'); ?></li>
                                     </ul>
                                 </dd>
                             </dl>
                         </div>
                     </div>
    <?php } ?>

    <?php
             } else {



                 if (count($pins) != 0) {
                     foreach ($pins as $pin) {
                         $userImageDetails = pathinfo($pin->user_image);


                         if (isset($userImageDetails['extension'])) {
                             $userProfileImage = $userImageDetails['filename'] . '_o.' . $userImageDetails['extension'];
                         }
    ?>
                         <div class="pin" id="pin_div_<?php echo $pin->pin_user_settings_id; ?>" style="width:auto">
                             <div class="pic  pic_show_functional">
                                 <a href="<?php echo JRoute::_('index.php?option=com_socialpinboard&view=boarddisplay&uid=' . $pin->user_id); ?>"  class="PinImage ImgLink">
                <?php
                         if ($pin->user_image == '') {
                ?>
                             <img src="<?php echo JURI::base() . 'components/com_socialpinboard/images/no_user.jpg'; ?>" title="<?php echo $pin->username; ?>"
                             alt="<?php echo rawurlencode($pin->user_image); ?>" class="avatar"/>
                     <?php
                         } else {
                     ?>
                             <img src="<?php echo JURI::base() . 'images/socialpinboard/avatars/' . $userProfileImage; ?>" title="<?php echo $pin->username; ?>" alt=""  class="pin_pic_img_a"/>
                <?php
                         }
                ?></a>
                     <p><?php echo ucfirst($pin->first_name) . ' ' . ucfirst($pin->last_name); ?></p>
                     <p class="location colorless"><?php echo ucfirst($pin->location) ?></p>

                     <div id="follow" >
                <?php
                         $resultuserfollow = socialpinboardModelSearch::followUser($pin->user_id);
                         if ($resultuserfollow['0'] != '') {
                             $follwercount = count($resultuserfollow);
                         } else {
                             $follwercount = '0';
                         }
                         if ($pin->user_id != $user->id && $pin->user_id != '' && $user->id != '') {
                             if (($resultuserfollow == 'all') || ($totalBoard == $follwercount)) {
                ?>
                                 <input type="button" name="unfollow" value="<?php echo JText::_('COM_SOCIALPINBOARD_UNFOLLOW'); ?>" id="unfollow" class="unfollow_btn" onclick="unfollowall(<?php echo $user->id . ',' . $pin->user_id; ?>,'<?php echo $fBoardId; ?>');" />
                <?php
                             } else {
                ?><div id="followalldiv">
                                     <input type="button" name="followall" value="<?php echo JText::_('COM_SOCIALPINBOARD_FOLLOW'); ?>" id="followall" onclick="followall(<?php echo $user->id . ',' . $pin->user_id; ?>,'<?php echo $fBoardId; ?>');"/>
                                 </div> <?php
                             }
                         } else {
                ?>
                             <div class="action-btn">
                                 <button class="unfollow_btn" type="button"> <?php echo "That's you"; ?></button>
                             </div>
<?php } ?>
                     </div>
                 </div>
             </div>
    <?php
                     }
                 } else {
    ?>
                     <div id="login_error_msg">

                         <div id="system-message-container">
                             <dl id="system-message">
                                 <dd class="error message"  style="font-size: 23px;">
                                     <ul>
                                         <li id="login_error_msg"><?php echo JText::_('COM_SOCIALPINBOARD_SORRY_USERS'); ?></li>
                                     </ul>
                                 </dd>
                             </dl>
                         </div>
                     </div>
    <?php
                 }
             }
    ?>

         </div>

<?php
             $querystring = "";
             if (JRequest::getVar('q')) {
                 if ($searchtype == 0 || $searchtype == 1 || $searchtype == 2) {
                     $querystring .= "&search=$searchtype";
                 }
                 $querystring .= "&amp;q=" . JRequest::getVar('q');

                 if (JRequest::getVar('pid')) {
                     $querystring .= "&pid=" . JRequest::getVar('pid');
                 }
             } else {
                 if (JRequest::getVar('serachVal')) {

                     $querystring .= "&search=0";
                 }
                 $querystring .= "&amp;q=" . JRequest::getVar('serachVal');

                 if (JRequest::getVar('pid')) {
                     $querystring .= "&amp;pid=$pid";
                 }
             }
?>

             <nav id="page-nav">
                 <a id="navpage" href="<?php echo $this->baseurl ?>/index.php/component/socialpinboard/?view=search<?php echo $querystring; ?>&amp;page=1"></a>
             </nav>

             <script>
                 var scr = jQuery.noConflict();  scr(document).ready(function($){

                     scr('.facebox').facebox({
                         loadingImage : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/loading.gif',
                         closeImage   : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/closelabel.png',
                         currentUrl    :document.location.href
                     }
                 );

                     var $container = scr('#container');

                     $container.masonry({
                         itemSelector : '.pin',

                         isFitWidth: true,
                         isResizable: true,
                         columnWidth: 200,
                         gutterWidth: 40
                     });
                     $container.find('div.pin').filter('div.pin').each(function()
                     {
                         var pin = scr(this);

                         var pinHeight = pin.height();

                         function checkSize()
                         {
                             var currHeight = pin.height();

                             if (pinHeight != currHeight) {
                                 $container.masonry('reload', function(){
                                     pinHeight = currHeight;
                                     setTimeout(checkSize, 50);
                                 });
                             } else {
                                 setTimeout(checkSize, 100);
                             }
                         }

                         checkSize();
                     });
                     $container.infinitescroll({
                         navSelector  : '#page-nav',    // selector for the paged navigation
                         nextSelector : '#page-nav a',  // selector for the NEXT link (to page 2)
                         itemSelector : '#container div.pin',     // selector for all items you'll retrieve
                         // extraScrollPx: 500,
                         loading: {
                             finishedMsg: 'No more pins to load.',
                             img: '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/loading.gif'
                         }
                     },
                     // trigger Masonry as a callback
                     function( newElements ) {
                         // hide new items while they are loading
                         var $newElems = scr( newElements ).css({ opacity: 0 });
                         // ensure that images load before adding to masonry layout
                         $newElems.imagesLoaded(function(){
                             // show elems now they're ready
                             $newElems.animate({ opacity: 1 });
                             $container.masonry( 'appended', $newElems, true );


                             scr(document).bind('beforeReveal.facebox', function() {
                                 scr("#facebox .content").empty();
                             });
                             scr('.facebox').facebox({
                                 loadingImage : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/loading.gif',
                                 closeImage   : '<?php echo JURI::base(); ?>/components/com_socialpinboard/images/closelabel.png',
                                 currentUrl    :document.location.href
                             }
                         );

                         });
                     }
                 );

                 });










                 function comment(pinId,userId) {


                     if(userId==0)
                     {
                         window.open('?option=com_socialpinboard&view=people','_self');
                         return false;
                     }
                     if(scr("#writecomment"+pinId).css('display')=="none"){
                         scr(".homecommentwrite").hide();
                         scr("#writecomment"+pinId).toggle();
                     }else{
                         scr(".homecommentwrite").hide();
                     }
                     scr("#commentContent"+pinId).focus();

                     scr('#container').masonry( 'reload' );


                 }

                 function doHomeComment(pinId,firstName,lastName,userImage,userUrl) {

                     var comment = scr("#commentContent"+pinId).val().replace(/^\s+|\s+$/g,"");
                     if(scr("#commentContent"+pinId).val()!="<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>"&&comment!=""){
                         document.getElementById("loading_grid_"+pinId).style.display="block";
                         scr.ajax({
                             type:"POST",
                             url:"?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcommentinfo",
                             data:{'pin_id':pinId,"comment":comment},
                             success:function(message) {
                                 document.getElementById("loading_grid_"+pinId).style.display="none";
                                 var obj = jQuery.parseJSON(message);
                                 var message = obj.comment;
                                 var user_comment_id=obj.comment_id;
                                 scr("#commentContent"+pinId).val('<?php echo JText::_('COM_SOCIALPINBOARD_ADD_A_COMMENT'); ?>');
                                 if(message != "error") {

                                     var message1 ='<li><div class="comment clearfix" ><a href="'+userUrl+'">';
                                     if(userImage=='')
                                     {
                                         message1 += '<img height="30" src="<?php echo JURI::base() ?>components/com_socialpinboard/images/no_user.jpg" title="'+firstName+lastName+'"  alt="'+firstName+lastName+'" width="30" class="ImgLink thumb-img"></a>';

                                     }else
                                     {
                                         message1 += '<img height="30" src="<?php echo JURI::base() ?>images/socialpinboard/avatars/'+userImage+'" title="'+firstName+lastName+'"  alt="'+firstName+lastName+'" width="30" class="ImgLink thumb-img"></a>';
                                     }

                                     message1 += '<p class="board_content"><a href="'+userUrl+'">'+firstName+' '+lastName+'</a>';
                                     message1+=' '+'<span>'+message+'</span></p></div></li>';

                                     scr('#commentDiv' + pinId + ' ul').append(message1);
                                     scr("#commentscountspan"+pinId).show();
                                     scr('#container').masonry( 'reload' );
                                     var span;
                                     var count = 0;
                                     if( scr("#commentscountspan"+pinId).text()){

                                         count =parseInt(scr("#commentscountspan"+pinId).text().substring(0,scr("#commentscountspan"+pinId).text().indexOf(" ")))+1;
                                         var counts =parseInt(scr("#commentsspan"+pinId).text().substring(0,scr("#commentscountspan"+pinId).text().indexOf(" ")))+1;
                                         span = count+" <?php echo JText::_('COM_SOCIALPINBOARD_COMMENTS') ?> ";
                                     }else{
                                         span= "1 <?php echo JText::_('COM_SOCIALPINBOARD_COMMENT') ?> ";
                        }
                        scr("#commentscountspan"+pinId).text(span);
                        scr("#commentsspan"+pinId).text(counts);

                    }
                }
            });
        }
    }

</script>