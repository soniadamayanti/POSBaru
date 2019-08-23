<?php
    require_once 'includes/header4.php';
?>
<section id="content">
	
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					
					<div class="row">
						<div class="col-md-12">
							<?php
                                if ($user_role == 1 || $user_role == 2) {
                                    ?>
							<a href="<?=base_url()?>index.php/transfer_stock/add_transfer_stock" style="text-decoration: none">
								<button class="btn btn-primary"  ><i class="fa fa-plus"></i><?php echo $lang_add_transfer_stock; ?></button>
							</a>
							<?php

                                }
                            ?>	
						</div>
					</div>
					<div class="row" style="margin-top: 10px;">
						<div class="col-md-12">
							
						<div class="table-responsive">
							<table class="table">
							    <thead>
							    	<tr>
								    	<th width="25%"><?php echo $lang_first_outlet; ?></th>
									    <th width="25%"><?php echo $lang_second_outlet; ?></th>
									    <th width="10%"><?php echo $lang_qty_transfer_stock; ?></th>
									    <th width="20%"><?php echo $lang_date; ?></th>
									    <th width="10%"><?php echo $lang_action; ?></th>
									</tr>
							    </thead>
								<tbody>
								<?php
                                    if (count($results) > 0) {
                                        foreach ($results as $data) {
                                            $first_outlet = $data->outlet_asal;
                                            $second_outlet = $data->outlet_tujuan;
                                            $qty = $data->qty; ?>
											<tr>
												<td><?php echo $first_outlet; ?></td>
												<td><?php echo $second_outlet; ?></td>
												<td><?php echo $qty; ?></td>
												<td><?php echo $data->date; ?></td>
											</tr>
								<?php
                                            unset($first_outlet);
                                            unset($second_outlet);
                                            unset($qty);
                                        }
                                    } else {
                                        ?>
										<tr class="no-records-found">
											<td colspan="3"><?php echo $lang_no_match_found; ?></td>
										</tr>
								<?php

                                    }
                                ?>
								</tbody>
							</table>
						</div>
							
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6" style="float: left; padding-top: 10px;">
							<?php echo $displayshowingentries; ?>
						</div>
						<div class="col-md-6" style="text-align: right;">
							<?php echo $links; ?>
						</div>
					</div>
					
				</div><!-- Panel Body // END -->
			</div><!-- Panel Default // END -->
		</div><!-- Col md 12 // END -->
	</div><!-- Row // END -->
	

</section>

	
	
	
<?php
    require_once 'includes/footer4.php';
?>