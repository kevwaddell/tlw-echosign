<div id="sigFormModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-tlw-red">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Complete and send</h4>
      </div>
      <div class="modal-body">
			
			<form method="post" action="" class="sigPad">
				
				<input type="hidden" name="signDate" value="<?php echo gmdate('g:ia, jS F, Y'); ?>">
				<input type="hidden" name="cref" value="<?php echo $data['ref']; ?>">
				
				<div class="form-group">
					<label for="name" class="text-center">Enter your full name:</label>
					<input type="text" name="name" id="name" class="name form-control text-center">
				</div>
				<p class="typeItDesc text-center">Review your signature</p>
				<p class="drawItDesc text-center">Draw your signature in the box below:</p>
			    <br>
			    <div class="sig-area text-center">
				    <ul class="sigNav">
					  <li class="drawIt"><a href="#draw-it" class="current">Draw It</a></li>
					  <li class="typeIt"><a href="#type-it">Type It</a></li>
					  <li class="clearButton"><a href="#clear">Clear</a></li>
				    </ul>
				    
				    <div class="sig sigWrapper">
				      <div class="typed"></div>
				      <canvas class="pad" height="100px" width="290px"></canvas>
				      <input type="hidden" name="output" class="output">
				    </div>
				    
				    <button type="submit" class="btn btn-success btn-lg btn-block caps text-center"><i class="glyphicon glyphicon-ok"></i> Accept agreement</button>
			    </div>
			    
			</form>


      </div>
      <div class="modal-footer bg-dkgray">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
