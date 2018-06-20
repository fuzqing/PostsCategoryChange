<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * PostsCategoryChange
 *
 * @package 批量更改文章分类
 * @author Fuzqing
 * @version 0.0.1
 * @link https://huangweitong.com
 */
class PostsCategoryChange_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Helper::addAction('imanage-posts', 'PostsCategoryChange_Action');
        Typecho_Plugin::factory('admin/manage-posts.php')->bottom = array(__CLASS__, 'render');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate()
    {
        Helper::removeAction('imanage-posts');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){}

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function render()
    {
        $db = Typecho_Db::get();

        $prefix = $db->getPrefix();

        $options = Typecho_Widget::widget('Widget_Options');

        $category_list = $db->fetchAll($db->select()->from($prefix.'metas')->where('type = ?', 'category'));


        $makeChange_url = Typecho_Common::url('/index.php/action/imanage-posts?do=change-category', $options->siteUrl);

        $category_html = '<select name="icategory" id="category" style="width: 100%">';

        $category_html .= '<option value="0">请选择一个分类</option>';

        foreach ($category_list as $category) {

            $category_html .= "<option value=\"{$category['mid']}\">{$category['name']}</option>";

        }
        $category_html .= '</select>';

        $script = <<<SCRIPT
        <script src="//cdn.bootcss.com/layer/3.1.0/layer.js"></script>
    <script>
         $(document).ready(function(){
            
            var html = '<li><a id="make-change" href="#">移动</a></li>'; 
            
            $(".dropdown-menu").append(html);
                        
            $("#make-change").click(function() {
                
                var params = $("form[name='manage_posts']").serialize();
                
                if(!params) {
                    layer.msg('至少选择一篇文章', function(){});
                    return false;
                } else {
                    mid = 0;
                    layer.open({
                        type: 1,
                        title:'移动到',
                        closeBtn: 0,
                        shadeClose: true,
                        btn: ['确定', '取消'],
                        content: '{$category_html}',
                        yes:function(index, layero) {
                            layer.close(index);
                            if(mid == undefined || mid == 0) {
                               layer.msg('请选择分类', function(){}); 
                               return false;
                            } else {
                                params = params + '&mid=' + mid;
                                var load_index = layer.load(2, {time: 10*1000});
                                $.post("{$makeChange_url}", params,function(data) {
                                    layer.close(load_index);
                                    if(data.code== -1) {
                                        layer.msg(data.msg, function(){});
                                    } else if(data.code == 1) {
                                        layer.msg(data.msg, function(){
                                            window.location.reload();
                                        });
                                    } else {
                                        console.log(data);
                                    }
                                },"json");
                            }
                      },
                      cancel: function(index, layero){ 
                          
                          mid = 0;
                          
                          layer.close(index)
                          
                          return false; 
                      } 
                      
                    });
                    $("#category").change(function(){
                       mid = $("#category").val();
                    });
                    
                }
            });
        });
    </script>
SCRIPT;
        echo $script;
    }
}
