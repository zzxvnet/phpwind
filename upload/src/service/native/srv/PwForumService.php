<?php
/**
 * 移动端版块相关
 * @fileName: PwForumService.php
 * @author: dongyong<dongyong.ydy@alibaba-inc.com>
 * @license: http://www.phpwind.com
 * @version: $Id
 * @lastchange: 2015-01-05 17:10:20
 * @desc: 
 **/
class PwForumService {

    /**
     * 从配置中获得移动端显示版块fids
     */ 
    public $fids=array();

    public function __construct(){
        $this->fids = $this->_getForumFids();
    }

    
    /**
     * 获所有一级版块 
     * 
     * @access public
     * @return void
     */
    public function getForumList(){
        $forumList = $this->_getForumDs()->getCommonForumList(PwForum::FETCH_MAIN | PwForum::FETCH_STATISTICS);
        foreach($forumList as $k=>$v){
            if($v['type']!='forum'){
                unset($forumList[$k]);
            }
        }
        return $this->_filterForumData($forumList);
    }


    /**
     * 获得移动端显示版块具体信息
     * 
     * @access public
     * @return void
     */
    public function fetchForum($fids){
        $forumList = $this->_getForumDs()->fetchForum($fids,PwForum::FETCH_MAIN | PwForum::FETCH_STATISTICS);
        return $this->_filterForumData($forumList);
    }

    /**
     * 移动端显示的模块fids 
     * 
     * @access public
     * @return void
     */
    private function _getForumFids(){
        $config = Wekit::C()->getConfigByName('native','forum.fids');
        $fids_array = unserialize($config['value']);
        return is_array($fids_array) ? array_keys($fids_array) : array();
    }

    /**
     * 过滤版块数据，不需要的字段过滤掉 
     * 
     * @param mixed $data 
     * @access private
     * @return void
     */
    private function _filterForumData($forumList){
        if( $forumList  ){
            foreach ($forumList as $key=>$forum) {
                $forumList[$key] = array(
                    'fid'=>$forum['fid'],
                    'name'=>$forum['name'],
                    'threads'=>$forum['threads'],
                    'todayposts'=>$forum['todayposts'],
                    'article'=>$forum['article'],
                    'posts'=>$forum['posts'],
                    'lastpost_time'=>Pw::time2str($forum['lastpost_time'], 'auto'),
                );  

            }   
        }
        return $forumList; 
    }

    private function _getForumDs(){
        return Wekit::load('forum.PwForum'); 
    }

}