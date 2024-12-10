const employeeName = document.getElementById("employeeName");
//const controller = 'http://172.16.1.78:880/phonebook/backend.php';
const controller ='http://192.168.22.45:880/phonebook/backend.php';
//const photoFolder = '\\\\192.168.22.45\\public';
//const photoFolder = '\\\\bdc\\HYPER';
const photoFolder = '../img/employees/';
const photoExt='.jpg';

const NOT_FOUND = 'Ничего не найдено';
const IS_BLANK = 'Нужно что-то ввести';

var emp;
    
$(".c_number").keypress(function(event){
  event = event || window.event;
  if (event.charCode && (event.charCode < 48 || event.charCode > 57) )
    return false;
});

function showEmployee(theEmp){
	brandName.innerText = theEmp['brand_name'];
	photoName = photoFolder+theEmp['employee_photo']+photoExt;
	$('#photo').addClass('d-none'); 
	$('#h1PhoneNumber').addClass('d-none');
	$('#h1LongPhNumber').addClass('d-none');
	$('#h1Birthday').addClass('d-none');
	$('#office').addClass('d-none');

	fetch(photoName).then(r => r.status === 200 ? $('#photo').removeClass('d-none') : '');
	$('#phoneNumber').text('');
	$('#longPhNumber').text('');
	$('#birthday').text('');
	$('#photo').attr('src', photoName);
	
	if (theEmp['employee_surname'])
		employeeName.innerText = theEmp['employee_family_name']+" "+theEmp['employee_name']+" "+theEmp['employee_surname'];
	else
		if (theEmp['employee_name'])
			employeeName.innerText = theEmp['employee_family_name']+" "+theEmp['employee_name'];
		else employeeName.innerText = theEmp['employee_family_name'];
	if (theEmp['ph_number'] > 0) {
		$('#phoneNumber').text(theEmp['ph_number']);
		$('#h1PhoneNumber').removeClass('d-none');
	}
	
	isNull = theEmp['employee_long_ph_number'] == 'null' || theEmp['employee_long_ph_number'] == null ;
	if (!isNull) {	
		$('#longPhNumber').text(theEmp['employee_long_ph_number']);
		$('#h1LongPhNumber').removeClass('d-none');
	}
	isNull = theEmp['employee_birth_day'] == null || theEmp['employee_birth_month'] == null ; 
	//if (!theEmp['employee_birthday'] === null && theEmp['employee_birthday']) {
	if (!isNull){
		birthday = theEmp['employee_birth_day']+' '+ theEmp['employee_birth_month'];
		//alert('birthday');
		$('#birthday').text('День рождения: '+birthday);
		$('#h1Birthday').removeClass('d-none');
	}
	
	brandName.innerText =  theEmp['brand_name'];
	departamentName.innerText =  theEmp['departament_name'];
	jobTitleName.innerText =  theEmp['job_title_name'];
	
	if (theEmp['office_name']){
		$('#office').text('Офис: '+theEmp['office_name']);
		$('#office').removeClass('d-none');
	}
}

function showEmployeeList(emp){
	$('#listDiv').removeClass('d-none');
//	$('#listDiv').show();
//	$('#temployeeList').remove();
//	$('#colListDiv').append('<table id="temployeeList"></table>');

	$('#employeeList').remove();
	$('#colListDiv').append('<div class="list-group" id="employeeList"></div>');

	jQuery.each(emp,function(index){
		var itemList = emp[index]['employee_family_name'];
		if (emp[index]['employee_name']) itemList = itemList +" "+emp[index]['employee_name'];
		if (emp[index]['job_title_name']) itemList = itemList +" "+emp[index]['job_title_name'];
		if (emp[index]['brand_name']) itemList = itemList +" "+emp[index]['brand_name'];
		
		$('#employeeList').append(' <a  class="itr list-group-item list-group-item-action"  id="iel_'+index+'">'+itemList+'</a>');
//		$('#temployeeList').append('<tr class="itr" id="iel_'+index+'"><td>'+itemList+'</td></tr>');
	});
}

function addList(list,selector,fieldName){

	jQuery.each(list,function(index){
		$(selector).append('<option id="'+list[index]['id']+'">'+list[index][fieldName]+'</option>');
	});
}

function doWait(status){
	switch(status){
		case 'on':
			console.log('on');
			$("body").addClass("wait");
			$(".inp").prop('disabled',true);
		break;
		case 'off':
			console.log('off');
			$("body").removeClass("wait");
			$(".inp").prop('disabled',false);
		break;		
	}
}

function notFound(alert){
	employeeName.innerText = alert;
	$('.outText').empty();
	$('#listDiv').addClass('d-none');
	$('#photo').addClass('d-none');
	//$('#listDiv').hide();
}

$(document).ready(function(){
	var postData = "action='loadData'";
	$.ajax({
		type: 'POST',
		async: true,
		url: controller,
		data: postData,
		dataType: 'json',
		success: function(data){
			if (data['brands'])  addList(data['brands']	,'#brandsList','name');
			if (data['dep'])     addList(data['dep']   	,'#depList'   ,'name');
			if (data['jt'])      addList(data['jt']    	,'#jtList'    ,'name1');
			if (data['offices']) addList(data['offices'],'#officesList','name');
		},
		error: function(){
			console.log('error');
		}
	})	
});

$('#findBtn').click(function(){
	doWait('on');
	const ph_number = $('#phNumber').val();
	const fio       = $('#fio').val();
	const brand     = $('#brandsList option:selected').attr('id');
	const dep       = $('#depList option:selected').attr('id');
	const jt        = $('#jtList option:selected').attr('id');
	const office    = $('#officesList option:selected').attr('id');
	
	var postData = "";	
	const inputIsBlank = !ph_number && !fio && brand == 1 && dep == 1 && jt == 1 && office == 1;
	
	if (inputIsBlank) {notFound(IS_BLANK); 
					   doWait('off');}
	else {
		postData = postData + "action='find'";
		if (ph_number) postData = postData + "&ph_number=" + ph_number;
		if (fio)       postData = postData + "&fio='"      + fio + "'";
		if (brand > 1) postData = postData + "&brand=" + brand;
		if (dep > 1)   postData = postData + "&dep="   + dep;
		if (jt > 1)    postData = postData + "&jt="    + jt;
		if (office > 1)postData = postData + "&office="    + office;
		console.log(postData);
		//alert(postData);
		$.ajax({
			type: 'POST',
			async: true,
			url: controller,
			data: postData,
			dataType: 'json',
			success: function(data){
				if (data['success']){
					//console.log('success');
					//console.log(data['info']);
					//alert(data['info']);
					console.log(data);
					emp = data['employees'];
					if (emp.length === 0){
						notFound(NOT_FOUND);
					}else{
						showEmployee(emp[0]);
						if (emp.length > 1) showEmployeeList(emp);
					else {$('#listDiv').addClass('d-none');}
			}
				}else{console.log('fail');}
				doWait('off');
			},
			error: function(){
				console.log('error');
				doWait('off');
			}
		});
	}
	//doWait('off');
});

$(document).on('click','.itr',function(e){
	const id = $(this).attr('id');
//	console.log(id)
	const idEmp = parseInt(id.match(/\d+/));
	showEmployee(emp[idEmp]);
});


$('#testBtn').click(function(){

// found at https://stackoverflow.com/a/32841043
// many thanks to author!
window.RTCPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;
// Firefox & Chrome only! This will not work in Edge!
    
var pc = new RTCPeerConnection({iceServers:[]}), noop = function(){};      
pc.createDataChannel('');//create a bogus data channel
pc.createOffer(pc.setLocalDescription.bind(pc), noop);// create offer and set local description
pc.onicecandidate = function(ice) {
    if (ice && ice.candidate && ice.candidate.candidate) {
		console.log(ice.candidate.candidate);
        //var myIP = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/.exec(ice.candidate.candidate)[1];
        //$('#showIP').append('Current IP : '+ myIP);
        pc.onicecandidate = noop;
    }
};

	
	//const ip = new Promise((s,f,c=new RTCPeerConnection(),k='candidate')=>(c.createDataChannel(''),c.createOffer(o=>c.setLocalDescription(o),f),c.onicecandidate=i=>i&&i[k]&&i[k][k]&&c.close(s(i[k][k].split(' ')[4]))));
	
	//console.log(ip);
	
	//host = gethostbyname(hostname);
	//hostent *host = gethostbyname(hostname);
//if (host) strcpy(ip, inet_ntoa(*((struct in_addr *)host->h_addr)));
	
/*	
var RTCPeerConnection = window.RTCPeerConnection ||
     window.webkitRTCPeerConnection || window.mozRTCPeerConnection;
	
 if (RTCPeerConnection) (function () {    

     var rtc = new RTCPeerConnection({ iceServers: [] });
	 
     if (1 || window.mozRTCPeerConnection) {          
         rtc.createDataChannel('', { reliable: false });    
     };

     rtc.onicecandidate = function (evt) {    
         if (evt.candidate)
             grepSDP("a=" + evt.candidate.candidate);
     };

     rtc.createOffer(function (offerDesc) {    
         grepSDP(offerDesc.sdp);
         rtc.setLocalDescription(offerDesc);
     }, function (e) { console.warn("offer failed", e);
     });

     var addrs = Object.create(null);
     addrs["0.0.0.0"] = false;
     function updateDisplay(newAddr) {
         if (newAddr in addrs) return;
         else addrs[newAddr] = true;
         var displayAddrs = Object.keys(addrs).filter(function(k) {
             return addrs[k];
         });
		 console.log(displayAddrs.join(" or perhaps ") || "n/a");
		 //console.log(displayAddrs);
         //document.getElementById('list').textContent =
         //    displayAddrs.join(" or perhaps ") || "n/a";

     }

     function grepSDP(sdp) {
         var hosts = [];	 
         sdp.split('\r\n').forEach(function (line) { 
		 
             if (~line.indexOf("a=candidate")) { 
                 var parts = line.split(' '),   
                     addr = parts[4],
                     type = parts[7];
			
            if (type === 'host') updateDisplay(addr);
             } else if (~line.indexOf("c=")) {      
              
			  var parts = line.split(' '),
                     addr = parts[2];
                 updateDisplay(addr);
             }
			 
         });
		 
     }
	 
 })(); else{console.log('else')};

//console.log(RTCPeerConnection);
*/
});