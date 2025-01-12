<?php include 'db_connect.php' ?>
<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
}
.imgs{
		margin: .5em;
		max-width: calc(100%);
		max-height: calc(100%);
	}
	.imgs img{
		max-width: calc(100%);
		max-height: calc(100%);
		cursor: pointer;
	}
	#imagesCarousel,#imagesCarousel .carousel-inner,#imagesCarousel .carousel-item{
		height: 60vh !important;background: black;
	}
	#imagesCarousel .carousel-item.active{
		display: flex !important;
	}
	#imagesCarousel .carousel-item-next{
		display: flex !important;
	}
	#imagesCarousel .carousel-item img{
		margin: auto;
	}
	#imagesCarousel img{
		width: auto!important;
		height: auto!important;
		max-height: calc(100%)!important;
		max-width: calc(100%)!important;
	}
</style>

<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

    <div class="container-fluid py-3">
        <div class="row" style="gap: 20px 0;">

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="border-bottom"><i class="fa fa-code"></i> : 
                        <?php 
                            $get_bsit = $conn->query("SELECT * FROM student_enroll WHERE course_to_be_enrolled = 'Bachelor of Science in Information Technology'");
                            echo $get_bsit->num_rows;
                        ?>
                        </h1>
                        <div style="text-align: center;">
                        <h5 class="mb-0">BSIT</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="border-bottom"><i class="fa fa-chart-bar"></i> : 
                        <?php 
                            $get_bsba = $conn->query("SELECT * FROM student_enroll WHERE course_to_be_enrolled = 'Bachelor of Science in Business Administration'");
                            echo $get_bsba->num_rows;
                        ?>
                    </h1>
                        <div style="text-align: center;">
                        <h5 class="mb-0">BSBA</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="border-bottom"><i class="fa fa-utensils"></i> : 
                        <?php 
                            $get_bshm = $conn->query("SELECT * FROM student_enroll WHERE course_to_be_enrolled = 'Bachelor of Science in Hotel Management'");
                            echo $get_bshm->num_rows;
                        ?>
                    </h1>
                        <div style="text-align: center;">
                        <h5 class="mb-0">BSHM</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="border-bottom"><i class="fa fa-chalkboard"></i> : 
                        <?php 
                            $get_bsed = $conn->query("SELECT * FROM student_enroll WHERE course_to_be_enrolled = 'Bachelor of Secondary Education'");
                            echo $get_bsed->num_rows;
                        ?>
                    </h1>
                        <div style="text-align: center;">
                        <h5 class="mb-0">BSED</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h1 class="border-bottom"><i class="fa fa-book"></i> : 
                        <?php 
                            $get_beed = $conn->query("SELECT * FROM student_enroll WHERE course_to_be_enrolled = 'Bachelor of Elementary Education'");
                            echo $get_beed->num_rows;
                        ?>
                    </h1>
                        <div style="text-align: center;">
                        <h5 class="mb-0">BEED</h5>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="chart">

                        </canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        var xValues = ["BSIT", "BSBA", "BSHM", "BSED", "BEED"];
var yValues = [<?php echo $get_bsit->num_rows ?>, <?php echo $get_bsba->num_rows ?>, <?php echo $get_bshm->num_rows ?>, <?php echo $get_bsed->num_rows ?>, <?php echo $get_beed->num_rows ?>];
var barColors = ["#436dfd", "#436dfd", "#436dfd", "#436dfd", "#436dfd"];

new Chart("chart", {
  type: "bar",
  data: {
    labels: xValues,
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "Total Enrolled Students"
    }
  }
});
    </script>
<script>
	$('#manage-records').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp)
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success')
                    setTimeout(function(){
                        location.reload()
                    },800)

                }
                
            }
        })
    })
    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person()
        }
    })
    $('#check').on('click',function(e){
            get_person()
    })
    function get_person(){
            start_load()
        $.ajax({
                url:'ajax.php?action=get_pdetails',
                method:"POST",
                data:{tracking_id : $('#tracking_id').val()},
                success:function(resp){
                    if(resp){
                        resp = JSON.parse(resp)
                        if(resp.status == 1){
                            $('#name').html(resp.name)
                            $('#address').html(resp.address)
                            $('[name="person_id"]').val(resp.id)
                            $('#details').show()
                            end_load()

                        }else if(resp.status == 2){
                            alert_toast("Unknow tracking id.",'danger');
                            end_load();
                        }
                    }
                }
            })
    }
</script>