<?php 
  include_once('header.php');
  $result = array();
  if($_SESSION['updateBooking'] == 1){
    $msg = '<div class="alert alert-success">Booking updated successfully</div>';
      $_SESSION['updateBooking'] ='';
  }
  $time= time();
  $time = date('H:i:s', strtotime('-1 hour'));
  if($_SESSION['restaurant_id'] != "")
    {
         $data = mysqli_query($GLOBALS['conn'],"SELECT db.delivery_id,db.* FROM delivery_bookings db JOIN restaurant_details rd ON db.restaurant_id = rd.restaurant_id Where db.restaurant_id = '".$_SESSION['restaurant_id']."' AND `delivery_date` > CURRENT_DATE() OR db.delivery_id in(SELECT db1.delivery_id FROM delivery_bookings db1  JOIN restaurant_details rd1 ON db1.restaurant_id = rd1.restaurant_id Where delivery_time > '".$time."' AND `delivery_date` = CURRENT_DATE()) order by db.delivery_id desc");
      
    }
    else
    {
      
       $data = mysqli_query($GLOBALS['conn'],"SELECT db.delivery_id,db.* FROM delivery_bookings db JOIN restaurant_details rd ON db.restaurant_id = rd.restaurant_id Where `delivery_date` > CURRENT_DATE() OR db.delivery_id in(SELECT db1.delivery_id FROM delivery_bookings db1  JOIN restaurant_details rd1 ON db1.restaurant_id = rd1.restaurant_id Where delivery_time > '".$time."' AND `delivery_date` = CURRENT_DATE()) order by db.delivery_id desc");

      
    }
  
  //Basic Validation  
  if(isset($_SESSION['msg']) == 'success'){
  if($_SESSION['msg'] == 'success'){
    $msg = '<div class="alert alert-warning">Venue added successfully</div>';
      $_SESSION['msg'] ='';
  }
  if($_SESSION['msg'] == 'successEdit'){
    $msg = '<div class="alert alert-warning">Venue edited successfully</div>';
      $_SESSION['msg'] ='';
  }
 }
  
?> 
     <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>
            
              <div class="row top_tiles">
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">                 
                  <div class="count pull-left">
                  
                  <?php echo count_number_of_records_delivery(1);
                   ?></div>
                   <div class="pull-right blk-padding"><h4 class="blk-padding-s">Pending</h4></div>
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                  <div class="count pull-left"><?php echo count_number_of_records_delivery(2);
                   ?></div>
                   <div class="pull-right blk-padding"><h4 class="blk-padding-s">Accepted</h4></div>
                  
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                 
                  <div class="count pull-left"><?php 
                  echo count_number_of_records_delivery(0); ?></div>
                   <div class="pull-right blk-padding"><h4 class="blk-padding-s">Declined</h4></div>
                  
                </div>
              </div>
              <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="tile-stats">
                     <div class="count pull-left"><?php 
                  if($_SESSION['restaurant_id'] != "")
                    {
                      echo mysqli_num_rows(mysqli_query($GLOBALS['conn'],"SELECT db.delivery_id,db.* FROM delivery_bookings db JOIN restaurant_details rd ON db.restaurant_id = rd.restaurant_id Where db.restaurant_id = '".$_SESSION['restaurant_id']."' AND `delivery_date` < CURRENT_DATE() AND `delivery_status`= 2 OR db.delivery_id in(SELECT db1.delivery_id FROM delivery_bookings db1  JOIN restaurant_details rd1 ON db1.restaurant_id = rd1.restaurant_id Where delivery_time < '".$time."' AND `delivery_date` = CURRENT_DATE()) order by db.delivery_id desc"));
                    }
                  else
                    {
                      echo mysqli_num_rows(mysqli_query($GLOBALS['conn'],"SELECT db.delivery_id,db.* FROM delivery_bookings db JOIN restaurant_details rd ON db.restaurant_id = rd.restaurant_id Where `delivery_date` < CURRENT_DATE() AND `delivery_status`= 2 OR db.delivery_id in(SELECT db1.delivery_id FROM delivery_bookings db1  JOIN restaurant_details rd1 ON db1.restaurant_id = rd1.restaurant_id Where delivery_time < '".$time."' AND `delivery_date` = CURRENT_DATE()) order by db.delivery_id desc"));
                    }
                      ?>
                  
                 </div>
                  <div class="pull-right blk-padding"><h4 class="blk-padding-s">Confirmed</h4></div>
                
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Venue Delivery List</h2>
                   <ul class="nav navbar-right panel_toolbox">
          <li>
           <input type="hidden" id = "delete_type" value ="booked_restaurant_delivery">
            <input type="hidden" value="<?php echo $_SESSION['restaurant_id'];?>" id="session">
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                    <?php echo $msg; ?>
                  <div class="x_content">
<!--
             <div class="col-md-3 col-sm-3 col-xs-3 form-group">
            <input type="text" style="width: 200px" name="bookingResDelivery" id="bookingResDelivery" class="form-control" />
            </div>
-->
                  <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                 
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                    <input type="text" style="width: 200px" name="booking_deliverytable_cal" id="booking_deliverytable_cal" placeholder="Search" readonly class="form-control" />
                  </div>
                  
                  <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                    <select name="booking_delivery_status" id="booking_delivery_status" class="form-control">
                      <option value="1">Pending</option>
                      <option value="2">Accepted</option>
                      <option value="0">Declined</option>
                    </select>
                  </div>
                </div>

                    <p class="text-muted font-13 m-b-30">
                    </p>
                    <table id="booking_deliverytable" class="table table-striped table-bordered">
                      <thead>
                        <tr>
                           <th width="12%">Name</th>
                           <th width="12%">Mobile</th>
                           <th width="12%">User Email</th>
                           <th width="12%">Date</th>
                           <th width="12%">Time</th>
                           <th width="12%">Delivery Status</th>
                           <th width="16%">Action</th>
                        </tr>
                      </thead>
                      <input type="hidden" id = "delete_type" value ="booked_restaurant">
                      <tbody id="statusContent">
                       <?php 
                             if($data)
                             { 
                               while($record = mysqli_fetch_assoc($data)){
                          ?>
                         <tr>
                          <td><?php echo $record['name'];?></td>
                          <td><?php echo $record['contact_no'];?></td>
                          <td><?php echo $record['delivery_email'];?></td>
                          <td><?php $date = date_create ($record['delivery_date']);
                                    echo date_format($date,"d M Y");?>
                          </td>
                          <td><?php echo $record['delivery_time'];?></td>
                         
                           <td><?php if($record['delivery_status']=="0"){ 
                                      echo "Declined";
                                    }
                                    else if($record['delivery_status']=="1"){
                                      echo "Pending";
                                    }
                                    else{
                                      echo "Accepted";             
                                    }
                            ?></td>
                          <td>
                          <?php if($record['delivery_status']=="1"){?>
                             <button type="button" id="confirm-<?php echo $record['delivery_id'];?>" class="btn btn-round btn-success">Accept</button>
                             <button type="button" class="btn btn-round btn-warning"  id="declines-<?php echo $record['delivery_id'];?>"data-toggle="modal" data-target="#myModal">Decline</button>
                              <?php }else if($record['delivery_status']=="2"){?>
                               <button type="button" class="btn btn-round btn-warning"  id="declines-<?php echo $record['delivery_id'];?>"data-toggle="modal" data-target="#myModal">Decline</button>
                              <?php }else{?>
                               <button type="button" id="confirm-<?php echo $record['delivery_id'];?>" class="btn btn-round btn-success">Accept</button>
                              <?php } ?>
                            <a href="edit_delivery.php?id=<?php echo encrypt_var($record['delivery_id']);?>&list=list" class="btn btn-round btn-info">Edit</a>
                            <a href="delivery_order.php?delivery_id=<?php echo encrypt_var($record['delivery_id']);?>" class="btn btn-round btn-info">View</a>
                    </td>
                         </tr>
                        <?php }}?> 
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Decline</h4>
        </div>
        <div class="modal-body">
      <div class="row">
        <div class="col-sm-6">
         <select class="form-control" id="declinedDev" name="declinedDev" value="">
           <option value="">Select Reason</option>
          <option value="Venue closed">Venue closed</option>
          <option value="No availability for selected date">No availability for selected date</option>
          <option value="No availability for selected time">No availability for selected time</option>
          <option value="Other">Other</option>
          </select>
          <input type="hidden" name="booking_res_idDev" id="booking_res_idDev">  
        </div>
        <div class="col-sm-6">
        <ul class="nav  panel_toolbox">                            
          <li><input type="text" class="form-control" placeholder="Enter custom reason"  id="reasonDev" name="reasonDev" style="display:none;" value=""/></li>
        </ul>
        </div>
      </div>
                 
      
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default btn-close" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-default btn-ok" id="yesDev">OK</button>
        </div>
      </div>
      
    </div>
  </div>
       
         <!-- Modal -->
<!-- Modal -->
<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
       
        <div id="timeData"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-default" id="timeYes">Ok</button>
      </div>
    </div>

  </div>
</div>
 <?php include_once('footer.php'); ?>
