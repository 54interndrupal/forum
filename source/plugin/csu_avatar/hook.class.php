<?php

!defined('IN_DISCUZ') && exit('Access Denied');

class plugin_csu_avatar {
	
	function plugin_csu_avatar() {
	}

	function viewthread_output() {
		global $_G;

		if(!in_array($_G['fid'], (array)unserialize($_G['cache']['plugin']['csu_avatar']['forums']))) {
			extract($_G['cache']['plugin']['csu_avatar']);
			$groups = (array)unserialize($groups);
			$lazyload = $_G['setting']['lazyload'] ? 'file' : 'src';
			$_G['setting']['pluginhooks']['viewthread_top'] .= '<style>.pls { width: '.($size + 40).'px; } .pls .avatar img { height: auto; max-width: '.$size.'px; width: auto; } .ie6 .pls .avatar img { width: expression(this.width > '.$size.' ? '.$size.' : true); } .bui { width: 510px !important; } .bui .m img { height: auto; max-width: '.$size.'px; width: auto; } .ie6 .bui .m img { width: expression(this.width > '.$size.' ? '.$size.' : true); } '.($size >= 180 ? '.pls dt { width: 70px; } .pls dd { width: 85px; }' : '').'</style>';

			global $postlist;
			foreach($postlist as $key => $post) {
				!in_array($post['groupid'], $groups) && $postlist[$key]['avatar'] = '<img '.$lazyload.'="'.avatar($post['authorid'], 'big', true).'" onerror="this.onerror=null;this.src=\''.$_G['setting']['ucenterurl'].'/images/noavatar_big.gif\'" />';
			}
		}
	}

}

class plugin_csu_avatar_forum extends plugin_csu_avatar{
	function forumdisplay_thread_output() {
		global $_G;
		if(empty($_G['forum_threadlist']) || $_G['forum']['picstyle']) {
			return array();
		}
		$return = array();
		$style = "<style>
		.csu_avatar {
			border: 1px solid #CCCCCC;
			float: left;
			margin-right: 8px;
			overflow: hidden;
			padding: 1px;
		}";
		if(intval($_G['cache']['plugin']['csu_avatar']['avatarwidth']) > 0) {
			$style .= "
			.csu_avatar img{
			width:".str_replace('px', '', $_G['cache']['plugin']['csu_avatar']['avatarwidth'])."px;
			height:".str_replace('px', '', $_G['cache']['plugin']['csu_avatar']['avatarheight'])."px;
			}";
		}
		$style .= '</style>';
		foreach($_G['forum_threadlist'] as $avatar_thread) {
			$return[] = '<div class="csu_avatar"><a href="home.php?mod=space&uid='.$avatar_thread['authorid'].'" title="'.htmlspecialchars(strip_tags($avatar_thread['author'])).'" c="1">'.avatar($avatar_thread['authorid'],small).'</a></div>';
		}
		$return[0] = $style.$return[0];
		return $return;
	}
}

class plugin_csu_avatar_group extends plugin_csu_avatar {}
?>