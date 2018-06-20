<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class PostsCategoryChange_Action extends Typecho_Widget implements Widget_Interface_Do
{

    public function action()
    {
        $user = Typecho_Widget::widget('Widget_User');
        $user->pass('administrator');
        $this->options = Typecho_Widget::widget('Widget_Options');
        $this->on($this->request->is('do=change-category'))->makeChange();
        exit;
    }

    public function makeChange()
    {
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        $cids = $this->request->filter('int')->getArray('cid');
        $mid = $this->request->filter('int')->get('mid');
        if(empty($cids)) {
            echo json_encode(['code'=>-1,'msg'=>'大佬，至少选择一篇文章！']);
            return;
        } else if(empty($mid)) {
            echo json_encode(['code'=>-1,'msg'=>'大佬，请选择一个分类！']);
            return;
        } else {
            $cid = implode(',',$cids);
            $select = 'SELECT cid FROM '.$prefix.'contents where cid in('.$cid.') and type="post"';
            $res = $db->fetchAll($db->query($select));
            if(empty($res)) {
                echo json_encode(['code'=>-1,'msg'=>'f**k,别特么瞎jb搞！']);
                return;
            } else {
                $post_cid = '';
                foreach ($res as $value) {
                    $post_cid .= $value['cid'].',';
                }
                $post_cid = trim($post_cid,',');
                
                $select = 'SELECT mid,type FROM '.$prefix.'metas where type="category"';

                $res = $db->fetchAll($db->query($select));
                
                $category_mid = '';
                foreach ($res as $value) {
                    $category_mid .= $value['mid'].',';
                }
                $category_mid = trim($category_mid,',');
                
                $res = $db->fetchAll($db->query($select));
                
                $update = $db->update($prefix.'relationships')->rows(array('mid'=>$mid))->where('cid in ('.$post_cid.') AND mid IN ('.$category_mid.')');
                $row = @$db->query($update);
                if($row) {
                    echo json_encode(['code'=>1,'msg'=>'本次成功更新'.$row.'篇文章！']);
                    return;
                } else {
                    echo json_encode(['code'=>-1,'msg'=>'更新失败']);
                    return;
                }
            }
        }
    }
}