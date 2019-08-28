<?php
    require_once 'includes/header4.php';

    $custDtaData = $this->Constant_model->getDataOneColumn('customers', 'id', $cust_id);

    if (count($custDtaData) == 0) {
        redirect(base_url());
    }

    $fullname = $custDtaData[0]->fullname;
    $email = $custDtaData[0]->email;
    $mobile = $custDtaData[0]->mobile;
?>

<section id="content">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $lang_view_history_customer; ?> : <?php echo $fullname; ?></h1>
		</div>
	</div><!--/.row-->
	
	
	<div class="row">
		<div class="col-md-12">
			
			<div class="card">
				<div class="card-body">
					
					<div class="row">
						<div class="col-md-12" style="text-align: right;">
							<?php
                                if ($user_role < 3) {
                                    ?>
							<a href="<?=base_url()?>index.php/customers/exportCustomerHistory?cust_id=<?php echo $cust_id; ?>" style="text-decoration: none;">
								<button type="button" class="btn btn-success" style="background-color: #5cb85c; border-color: #4cae4c;">
									<?php echo $lang_export_to_excel; ?>
								</button>
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
									    	<th width="7%"><?php echo $lang_sale_id; ?></th>
									    	<th width="7%"><?php echo $lang_type; ?></th>
									    	<th><?php echo $lang_date_time; ?></th>
									    	<th><?php echo $lang_products; ?></th>
									    	<th><?php echo $lang_quantity; ?></th>
										    <th><?php echo $lang_sub_total; ?> (<?php echo $currency; ?>)</th>
										    
										    <th><?php echo $lang_grand_total; ?> (<?php echo $currency; ?>)</th>
										    <th><?php echo $lang_action; ?></th>
										</tr>
								    </thead>
									<tbody>
							
<?php
    $total_subTotal_amt = 0;
    $total_taxTotal_amt = 0;
    $total_grandTotal_amt = 0;

    $historyData = $this->Constant_model->getDataOneColumnSortColumn('sales', 'customer_id', "$cust_id", 'id', 'DESC');

    if (count($historyData) > 0) {
        for ($h = 0; $h < count($historyData); ++$h) {
            $sales_id = $historyData[$h]->id;
            $dtm = date("$dateformat   H:i A", strtotime($historyData[$h]->created_date));
            $subTotal = $historyData[$h]->total_deal;
            $grandTotal = $historyData[$h]->total_deal;

            $total_subTotal_amt += $subTotal;
            $total_grandTotal_amt += $grandTotal;

            $pcodeArray = array();
            $pnameArray = array();
            $qtyArray = array();

            if ($order_type == '1') {                // Order;

                $oItemResult = $this->db->query("SELECT * FROM order_items WHERE order_id = '$sales_id' ORDER BY id ");
                $oItemRows = $oItemResult->num_rows();
                if ($oItemRows > 0) {
                    $oItemData = $oItemResult->result();

                    for ($t = 0; $t < count($oItemData); ++$t) {
                        $oItem_pcode = $oItemData[$t]->product_code;
                        $oItem_pname = $oItemData[$t]->product_name;
                        $oItem_qty = $oItemData[$t]->qty;

                        array_push($pcodeArray, $oItem_pcode);
                        array_push($pnameArray, $oItem_pname);
                        array_push($qtyArray, $oItem_qty);

                        unset($oItem_pcode);
                        unset($oItem_pname);
                        unset($oItem_qty);
                    }

                    unset($oItemData);
                }
                unset($oItemResult);
                unset($oItemRows);
            } elseif ($order_type == '2') {    // Return;

                $rItemResult = $this->db->query("SELECT * FROM return_items WHERE order_id = '$sales_id' ORDER BY id ");
                $rItemRows = $rItemResult->num_rows();
                if ($rItemRows > 0) {
                    $rItemData = $rItemResult->result();
                    for ($r = 0; $r < count($rItemData); ++$r) {
                        $rItem_pcode = $rItemData[$r]->product_code;
                        $rItem_qty = $rItemData[$r]->qty;

                        $productData = $this->Constant_model->getDataOneColumn('products', 'code', $rItem_pcode);
                        $rItem_pname = $productData[0]->name;

                        array_push($pcodeArray, $rItem_pcode);
                        array_push($pnameArray, $rItem_pname);
                        array_push($qtyArray, $rItem_qty);

                        unset($rItem_pcode);
                        unset($rItem_qty);
                        unset($rItem_pname);
                    }
                    unset($rItemData);
                }
                unset($rItemResult);
                unset($rItemRows);
            } ?>			
			<tr>
				<td>
					<?php
                        if ($order_type == '1') {
                            ?>
					<a href="<?=base_url()?>index.php/pos/view_invoice?id=<?php echo $sales_id; ?>" style="text-decoration: none;" target="_blank">
					<?php	
                        }
            if ($order_type == '2') {
                ?>
					<a href="<?=base_url()?>index.php/returnorder/printReturn?return_id=<?php echo $sales_id; ?>" style="text-decoration: none;" target="_blank">
					<?php

            } ?>
						<?php echo $sales_id; ?>
					</a>
				</td>
				<td style="font-weight: bold;">
					<?php
                        if ($order_type == '1') {
                            echo 'Sale';
                        }
            if ($order_type == '2') {
                echo 'Return';
            } ?>
				</td>
				<td><?php echo $dtm; ?></td>
				<td>
					<?php
                        if (count($pcodeArray) > 0) {
                            echo $pnameArray[0].' ['.$pcodeArray[0].']';
                        } ?>
				</td>
				<td>
					<?php
                        if (count($qtyArray) > 0) {
                            echo $qtyArray[0];
                        } ?>
				</td>
				<td><?php echo $total_items; ?></td>
				<td><?php echo number_format($subTotal, 2); ?></td>
				<td><?php echo number_format($tax, 2); ?></td>
				<td><?php echo number_format($grandTotal, 2); ?></td>
				<td>
					<?php
                        if ($order_type == '1') {
                            ?>
							<a href="<?=base_url()?>index.php/returnorder/create_return?cust_id=<?php echo $cust_id; ?>&sales_id=<?php echo $sales_id; ?>" style="text-decoration: none;">
								<button class="btn btn-primary" style="padding: 4px 12px;">
									<?php echo $lang_create_return_order; ?>
								</button>
							</a>
					<?php

                        } else {
                            echo '-';
                        } ?>
				</td>
			</tr>
			<?php
                if (count($pcodeArray) > 1) {
                    for ($p = 1; $p < count($pcodeArray); ++$p) {
                        ?>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<?php
                                    echo $pnameArray[$p].' ['.$pcodeArray[$p].']'; ?>
							</td>
							<td>
								<?php
                                    echo $qtyArray[$p]; ?>
							</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
			<?php

                    }
                } ?>
<?php

        } ?>
			<tr>
				<td colspan="6" align="center" style="border-top: 1px solid #010101;"><b><?php echo $lang_total; ?></b></td>
				<td style="border-top: 1px solid #010101;">
					<b><?php echo number_format($total_subTotal_amt, 2)." ($currency)"; ?></b>
				</td>
				<td style="border-top: 1px solid #010101;">
					<b><?php echo number_format($total_taxTotal_amt, 2)." ($currency)"; ?></b>
				</td>
				<td style="border-top: 1px solid #010101;">
					<b><?php echo number_format($total_grandTotal_amt, 2)." ($currency)"; ?></b>
				</td>
				<td style="border-top: 1px solid #010101;"></td>
			</tr>		
<?php

    } else {
        ?>

			<tr>
				<td colspan="6"><?php echo $lang_no_match_found; ?></td>
			</tr>
<?php	
    }
?>
									</tbody>
								</table>
							</div>
							
						</div>
					</div>
					
					
					
					
					
				</div><!-- Panel Body // END -->
			</div><!-- Panel Default // END -->
			
			
			<a href="<?=base_url()?>index.php/customers/view" style="text-decoration: none;">
				<div class="btn btn-success" > 
					<i class="icono-caretLeft" style="color: #FFF;"></i><?php echo $lang_back; ?>
				</div>
			</a>
			
		</div><!-- Col md 12 // END -->
	</div><!-- Row // END -->
</section>

	
	
	
<?php
    require_once 'includes/footer4.php';
?>