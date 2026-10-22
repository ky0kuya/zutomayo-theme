<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$ztmyEscape = static function ($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};
?>

<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
    <h3 style="color:var(--green); border-bottom:2px solid var(--green); display:inline-block; margin-bottom: 15px;">
        /// INTERCEPTED_SIGNALS (<?php $this->commentsNum(_t('0'), _t('1'), _t('%d')); ?>)
    </h3>
    
    <div class="comment-list">
        <?php while($comments->next()): ?>
        <div class="comment-item" id="<?php $comments->theId(); ?>" style="border:1px solid #333; padding:10px; margin-bottom:15px; background:#0a0a0a;">
            <div class="comment-author" style="color:var(--purple); font-weight:bold; margin-bottom:5px;">
                [SIGNAL FROM: <?php echo $ztmyEscape($comments->author); ?>]
                <span style="float:right; opacity:0.6; font-size:0.8rem;"><?php $comments->date('Y-m-d H:i'); ?></span>
            </div>
            <div class="comment-content" style="line-height:1.6;">
                <?php $comments->content(); ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <div class="comment-page" style="margin: 20px 0; text-align: center;">
        <?php $comments->pageNav('«', '»', 1, '...', array('wrapTag' => 'ul', 'wrapClass' => 'page-navigator', 'itemTag' => 'li', 'textTag' => 'span', 'currentClass' => 'current', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>
    </div>
    <?php endif; ?>

    <?php if($this->allow('comment')): ?>
    <div id="<?php $this->respondId(); ?>" class="respond">
        <h3 style="color:var(--green); border-bottom:2px solid var(--green); display:inline-block; margin-top: 30px; margin-bottom: 15px;">
            /// TRANSMIT_NEW_SIGNAL
        </h3>
        
        <form method="post" action="<?php echo $ztmyEscape($this->commentUrl); ?>" id="comment-form" role="form">
            <?php $security = $this->widget('Widget_Security'); ?>
            <input type="hidden" name="_" value="<?php echo $ztmyEscape($security->getToken($this->request->getRequestUrl())); ?>">
            
            <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                <input type="text" name="author" placeholder="IDENTITY (Required)" value="<?php $this->remember('author'); ?>" required style="background:#000; border:1px solid var(--purple); color:var(--green); flex:1; padding:10px; font-family:'VT323';" />
                <input type="email" name="mail" placeholder="EMAIL (Required)" value="<?php $this->remember('mail'); ?>" <?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?> style="background:#000; border:1px solid var(--purple); color:var(--green); flex:1; padding:10px; font-family:'VT323';" />
            </div>
            <textarea name="text" rows="5" placeholder="ENTER MESSAGE DATA..." required style="background:#000; border:1px solid var(--purple); color:var(--green); width:100%; padding:10px; margin-bottom:10px; font-family:'VT323'; box-sizing: border-box;"><?php $this->remember('text'); ?></textarea>
            <button type="submit" class="submit-btn" style="background:var(--purple); color:#fff; border:none; padding:10px 20px; cursor:pointer; width:100%; font-family:'VT323'; font-size:1.2rem;">SEND_SIGNAL</button>
        </form>
    </div>
    <?php else: ?>
    <h3 style="color:red;">/// CHANNEL_CLOSED</h3>
    <?php endif; ?>
</div>
