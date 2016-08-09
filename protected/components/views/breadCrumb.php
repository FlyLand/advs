<?php if( !empty( $this->links ) ){ ?>
<ul class="breadcrumb">
	<li>您的位置：</li>
    <li>
        <a href="<?php echo $this->homeLinks['url']; ?>"><?php echo $this->homeLinks['name']; ?></a>
        <span><?php echo $this->delimiter; ?></span>
    </li>
    <?php
    foreach ($this->links as $crumb) {
        if (is_array($crumb)&&isset($crumb['name'])) {
            ?>
            <li class="divider">
                <?php echo CHtml::link($crumb['name'], $crumb['url']); ?>
                <span><?php echo $this->delimiter; ?></span>
            </li>
            <?php
        } else {
            ?>
            <li class="active"><?php echo $crumb; ?></li>
            <?php
        }
    }
    ?>
</ul>
<?php }?>