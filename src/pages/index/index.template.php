<div class="yui3-g">
	<div class="yui3-u-1-4 sidebar">
		<div class="mod">
			<h2>Featured</h2>
			<ul class="featured">
				<?php
					foreach ( new \dao\answers('get',array(array('featured'=>1),array('sort'=>array('added'=>-1),'per'=>3))) as $item ) {
						echo "
							<li>
								<a href='".b::url('profile',array('name'=>$item->by->login))."'>
									<h3>{$item->by->name}</h3>
									<cite>{$item->job}</cite>
									<address>{$item->loc}</address>									
								</a>
							</li>						
						";
					}
				?>
			</ul>	

			<h2>Near You</h2>
			<ul class="featured near">
				<?php
					foreach ( $near as $item ) {
						echo "
							<li>
								<a href='".b::url('profile',array('name'=>$item->by->login))."'>
									<h3>{$item->by->name}</h3>
									<cite>{$item->job}</cite>
									<address>{$item->loc}</address>
								</a>
							</li>						
						";
					}
				?>			
			</ul>
			
			<ul class="featured">
				<li class="browse"><a href="{$url.browse}">Browse All Profiles</a></li>
				<li class="submit"><a href="https://github.com/traviskuhl/4q/blob/master/README.md">Submit Your Profile</a></li>			
			</ul>				
			
		</div>
	</div>	
	<div class="yui3-u-3-4 answers">		
		<div class="user">
			<script type="text/javascript">
			 B.add('l',function(){
			 	B.Obj.cl.FQ.loadUser('{$answer.id}');
			 });
			</script>
		</div>
		<?php echo $answer->text->html ?>	
			
		<cite class="info">
			<strong>Authored:</strong> <?php echo $answer->ago('commit_author'); ?> (<?php echo date('m/d/y g:ia',$answer->commit->author); ?>) | 
			<strong>Committed:</strong> <?php echo $answer->ago('commit_date'); ?> (<?php echo date('m/d/y g:ia',$answer->commit->date); ?>) |
			<a target="_new" href="https://github.com{$answer.commit.url}">Commit</a> (<?php echo substr($answer->commit->id,0,20); ?>) |
			<a target="_new" href="https://github.com/traviskuhl/4q/raw/master/{$answer.commit.file}">Raw</a>
		</cite>	
		
		<div class="fb">
			<iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode(SELF); ?>&amp;send=true&amp;layout=standard&amp;width=450&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:80px;" allowTransparency="true"></iframe>
		</div>		
		
		<div class="ask">
			<h3>Ask {$answer.by.name} a Question</h3>
			{% comments(asset:answer, id:{$answer.id}) %}
		</div>
		
	</div>
</div>