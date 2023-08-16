<?php
/* [view]
 * <?php $this->widget('Flash'); ?>
 * [controller]
 * Yii::app()->user->setFlash('success', 'メッセージ');
 */
class Flash extends CWidget
{
    public $keys = array(
        'success',
        'info',
        'warning',
        'error',
    );
 
    public $template = '<div class="flash-{key}">{message}</div>';
    
    /**
     * @see CWidget::run()
     */
    public function run()
    {
        if (is_string($this->keys)) {
            $this->keys = array($this->keys);
        }
            
        foreach ($this->keys as $key) {
            if (Yii::app()->getUser()->hasFlash($key)) {
                echo strtr($this->template, array(
                    '{key}' => $key,
                    '{message}' => Yii::app()->getUser()->getFlash($key),
                ));
            }
        }
    }
}