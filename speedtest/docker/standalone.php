<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no" />
<meta charset="UTF-8" />
<link rel="shortcut icon" href="favicon.ico">
<script type="text/javascript">
function onLoad() {
var now = new Date().getTime();
var page_load_time = now - performance.timing.navigationStart;
I("loadText").textContent=format(page_load_time);
}
</script>
<script type="text/javascript" src="speedtest.js"></script>
<script type="text/javascript">
function I(i){return document.getElementById(i);}
//INITIALIZE SPEEDTEST
var s=new Speedtest(); //create speedtest object
<?php if(getenv("TELEMETRY")=="true"){ ?>
s.setParameter("telemetry_level","basic");
<?php } ?>
<?php if(getenv("DISABLE_IPINFO")=="true"){ ?>
s.setParameter("getIp_ispInfo","false");
<?php } ?>
<?php if(getenv("DISTANCE")){ ?>
s.setParameter("getIp_ispInfo_distance","<?=getenv("DISTANCE") ?>");
<?php } ?>
var meterBk=/Trident.*rv:(\d+\.\d+)/i.test(navigator.userAgent)?"#EAEAEA":"#80808040";
var dlColor="#ffff";
    ulColor="#ffff";
var progColor=meterBk;
//CODE FOR GAUGES
function drawMeter(c,amount,bk,fg,progress,prog){
var ctx=c.getContext("2d");
var dp=window.devicePixelRatio||1;
var cw=c.clientWidth*dp, ch=c.clientHeight*dp;
var sizScale=ch*0.0055;
if(c.width==cw&&c.height==ch){
  ctx.clearRect(0,0,cw,ch);
}else{
  c.width=cw;
  c.height=ch;
}
ctx.beginPath();
ctx.strokeStyle=bk;
ctx.lineWidth=12*sizScale;
ctx.strokeRect(0,c.height-58*sizScale,2*(c.height/1.8-ctx.lineWidth),(Math.PI/24)*(c.height/1.8-ctx.lineWidth));
if(typeof progress !== "undefined"){
  ctx.fillStyle=prog;
  ctx.fillRect(0,c.height-58*sizScale,(2*(c.height/1.8-ctx.lineWidth))*progress,(Math.PI/24)*(c.height/1.8-ctx.lineWidth));
}
}
function mbpsToAmount(s){
return 1-(1/(Math.pow(1.3,Math.sqrt(s))));
}
function format(d){
d=Number(d);
if(d<10) return d.toFixed(2);
if(d<100) return d.toFixed(1);
return d.toFixed(0);
}
//UI CODE
var uiData=null;
function startStop(){
if(s.getState()==3){
  //speedtest is running, abort
  s.abort();
  data=null;
  I("startStopBtn").className="";
  initUI();
}else{
  //test is not running, begin
  I("startStopBtn").className="running";
  I("shareArea").style.display="none";
  s.onupdate=function(data){
      uiData=data;
  };
  s.onend=function(aborted){
      I("startStopBtn").className="";
      updateUI(true);
      if(!aborted){
          //if testId is present, show sharing panel, otherwise do nothing
          try{
              var testId=uiData.testId;
              if(testId!=null){
                  var shareURL=window.location.href.substring(0,window.location.href.lastIndexOf("/"))+"/results/?id="+testId;
                  I("resultsImg").src=shareURL;
                  I("resultsURL").value=shareURL;
                  I("testId").innerHTML=testId;
                  I("shareArea").style.display="";
              }
          }catch(e){}
      }
  };
  s.start();
}
}
//this function reads the data sent back by the test and updates the UI
function updateUI(forced){
if(!forced&&s.getState()!=3) return;
if(uiData==null) return;
var status=uiData.testState;
I("ip").textContent=uiData.clientIp;
I("dlText").textContent=(status==1&&uiData.dlStatus==0)?"...":format(uiData.dlStatus);
drawMeter(I("dlMeter"),mbpsToAmount(Number(uiData.dlStatus*(status==1?oscillate():1))),meterBk,dlColor,Number(uiData.dlProgress),progColor);
I("ulText").textContent=(status==3&&uiData.ulStatus==0)?"...":format(uiData.ulStatus);
drawMeter(I("ulMeter"),mbpsToAmount(Number(uiData.ulStatus*(status==3?oscillate():1))),meterBk,ulColor,Number(uiData.ulProgress),progColor);
I("pingText").textContent=format(uiData.pingStatus);
I("jitText").textContent=format(uiData.jitterStatus);
}
function oscillate(){
return 1+0.02*Math.sin(Date.now()/100);
}
//update the UI every frame
window.requestAnimationFrame=window.requestAnimationFrame||window.webkitRequestAnimationFrame||window.mozRequestAnimationFrame||window.msRequestAnimationFrame||(function(callback,element){setTimeout(callback,1000/60);});
function frame(){
requestAnimationFrame(frame);
updateUI();
}
frame(); //start frame loop
//function to (re)initialize UI
function initUI(){
drawMeter(I("dlMeter"),0,meterBk,dlColor,0);
drawMeter(I("ulMeter"),0,meterBk,ulColor,0);
I("dlText").textContent="";
I("ulText").textContent="";
I("pingText").textContent="";
I("jitText").textContent="";
I("ip").textContent="";
}
</script>
<style type="text/css">
html,body{
  border:none; padding:0; margin:0; height:100%;
  background:#FFFFFF;
  color:#0F1E57;
  display:inline-block;
  margin:0 auto;
  background-color:rgba(0,0,0,0);
  margin-left: auto;
  margin-right: auto;
  /*border:0.15em solid #bababa;
  border-radius:0.3em;
  transition:all 0.3s;
  box-sizing:border-box;
  width:100em; height:5em;
  line-height:2.7em;
  cursor:pointer;
  align-items:left;
  box-shadow: 0 0 0 rgba(0,0,0,0.1), inset 0 0 0 rgba(0,0,0,0.1);
  fill: gray*/
}
body{
 text-align:center;
 background: #F7F8FA;
 background-size: 2100px 1500px;
 display: block;
 font-family: sans-serif;
 align: left;
 margin-left: auto;
 margin-right: auto;
 margin-block-start: 0;
 margin-block-end: 0;
}
p.h-tab{
 display: flex;
 flex-wrap: wrap;
 align-items: center;
 justify-content: space-between;
 background-color: #F7F8FA;
 position: relative;
 left: 61px;
 height: 40px;
 width: 95%;
 transition: all .2s;
 padding: 10px 20px 10px 0;
 border-bottom: 1px solid #DCDCDC;
 margin-block-start: 0;
 margin-block-end: 0;
}
p.v-tab{
 display: flex;
 position: absolute;
 top: 0;
 left: 0;
 bottom: 0;
 z-index: 1000;
 flex-direction: column;
 width: 60px;
 background-color: #F3F4F9;
 border-right: 1px solid #DCDCDC;
 margin-block-start: 0;
 margin-block-end: 0;
}
p.text:before{
 font-family: "Open Sans";
 font-style: normal;
 font-weight: 600;
 font-size: 24px;
 line-height: 33px;
 color: #0F1E57;
 position: absolute;
 left: 75px;
 top: 10px;
 order: 5;
 content: "Speedtest";
}
p.text-ams:before{
 font-family: "Gurmukhi Sangam MN";
 font-style: normal;
 font-weight: normal;
 font-size: 18px;
 line-height: 26px;
 color: #000000;
 position: absolute;
 left: 105px;
 top: 105px;
 order: 5;
 content: "ams9-ams"
}
#loadPageArea{
  display:inline-block;
  margin:0 auto;
  color:#6060AA;
  background-color:rgba(0,0,0,0);
  border:0.15em solid ##3075FF;
  border-radius:0.3em;
  transition:all 0.3s;
  box-sizing:border-box;
  width:8em; height:3em;
  line-height:2.7em;
}
#startStopBtn{
  display:inline-block;
  margin:0 auto;
  color:#0E1F57;
  background-color: #FFFFFF;
  transition:all 0.3s;
  box-sizing:border-box;
  width:5em; height:2em;
  line-height:2.7em;
  cursor:pointer;
  position: absolute;
  top: 105px;
  left: 105px;
  font-weight:400;
  font-size:16px;
  z-index:3;
}
#startStopBtn:hover{
  color: #5073FE;
  background-color: #FFFFFF;
}
#startStopBtn.running{
  background-color:#FFFFFF;
  color:#DC143C;
}
#startStopBtn:before{
  content:"Start test";
}
#startStopBtn.running:before{
  content:"Abort test";
}
div.boxWrapper{
 display: flex;
 flex-flow: row wrap;
 font-weight: bold;
 text-align: center;
 padding: 10px;
 flex: 1 100%;
}
p.main-box{
 position: absolute;
 min-width: 200px;
 word-wrap: break-word;
 background-color: #fff;
 background-clip: border-box;
 border: 1px solid #e9ebf1;
 border-radius: 5px;
 order: -1;
 top:83px;
 left:80px;
 height:89vh;
 width:94%;
 margin-block-start: 0;
 margin-block-end: 0;
}
p.small-tab{
 position: absolute;
 border-bottom: 1px solid  #F7F8FA;
 transition:all 0.3s;
 box-sizing:border-box;
 line-height:0em;
 align: left;
 box-shadow: 0 12px 50px 0 rgba(11,22,64,.03), inset 0 0 0 rgba(0,0,0,0.1);
 background-color: #EEF1F6;
 order: -1;
 top: 149px;
 left: 81px;
 height: 40px;
 width:94%;
}
.small-tab-text{
 display: flex;
 flex-flow: row wrap;
 justify-content: space-around;
 list-style: none;
 margin: 0;
 color: #a0a8c2;
 padding-top: 105px;
 width:97%;
 z-index:2;
}
p.text-ams:before{
 font-family: "Gurmukhi Sangam MN";
 font-style: normal;
 font-weight: normal;
 font-size: 18px;
 line-height: 26px;
 color: #000000;
 position: absolute;
 left: 121px;
 top: 110px;
 order: 100;
 content: "ams9-ams"
}

p.ams9-ams{
 position: absolute;
 border-bottom: 1px solid #E5EAF7;
 transition:all 0.3s;
 box-sizing: border-box;
 line-height:0em;
 align: left;
 box-shadow: 0 12px 50px 0 rgba(11,22,64,.03), inset 0 0 0 rgba(0,0,0,0.1);
 background-color: #f7faff;
 order: -1;
 width:94%;
 height:69px;
 top:188px;
 left:81px;
}
.ams-group{
 display: flex;
 flex-flow: row wrap;
 justify-content: space-around;
 list-style: none;
 margin: 0;
 color: #0000;
 padding-top: 105px;
 padding-left: 105px;
 width:97%;
 z-index:2;
}
#test{
  margin-top:2.5em;
  margin-bottom:14em;
}
/*div.bla {
margin: -50px -50px;
padding: 100px;
background-color: #ffffff;
}*/
div.testArea{
  display:inline-block;
  width:16em;
  height:12.5em;
  position:relative;
  box-sizing:border-box;
}
div.testArea2{
  display:inline-block;
  width:14em;
  height:7em;
  position:relative;
  box-sizing:border-box;
  text-align:center;
}
div.testArea3{
  display:inline-block;
  width:14em;
  height:3.5em;
  position:relative;
  box-sizing:border-box;
  text-align:center;
}
div.testArea div.testName{
  position:absolute;
  top:0.1em; left:0;
  width:100%;
  font-size:1.4em;
  z-index:9;
}
div.testArea2 div.testName{
  display:block;
  text-align:center;
  font-size:1.4em;
}
div.testArea3 div.testName{
  display:inline-block;
  text-align:center;
  font-size:1.2em;
}
div.testArea div.meterText{
  position:absolute;
  bottom:1.55em; left:0;
  width:100%;
  font-size:2.5em;
  z-index:9;
}
div.testArea2 div.meterText{
  display:inline-block;
  font-size:2.5em;
}
div.testArea3 div.meterText{
  display:inline-block;
  font-size:1.2em;
}
div.meterText:empty:before{
  content:"0.00";
}
div.meterText1:empty:before{
  content:"0.00";
}
div.testArea div.unit{
  position:absolute;
  bottom:2em; left:0;
  width:100%;
  z-index:9;
}
div.testArea2 div.unit{
  display:inline-block;
}
div.testArea3 div.unit{
  display:inline-block;
}
div.testArea canvas{
  position:absolute;
  top:0; left:0; width:100%; height:50%;
  z-index:1;
}
div.testGroup{
  display:block;
  margin: 0 auto;
}
#shareArea{
  width:95%;
  max-width:40em;
  margin:0 auto;
  margin-top:2em;
}
#shareArea > *{
  display:block;
  width:100%;
  height:auto;
  margin: 0.25em 0;
}
#privacyPolicy{
  position:fixed;
  top:2em;
  bottom:2em;
  left:2em;
  right:2em;
  overflow-y:auto;
  width:auto;
  height:auto;
  box-shadow:0 0 3em 1em #000000;
  z-index:999999;
  text-align:left;
  background-color:#FFFFFF;
  padding:1em;
  /*display:inline-block;
  margin:0 auto;
  color:#a805ff;
  background-color:rgba(0,0,0,0);
  border:0.15em solid #6060FF;
  border-radius:0.3em;
  transition:all 0.3s;
  box-sizing:border-box;
  width:8em; height:3em;
  line-height:2.7em;
  cursor:pointer;
  box-shadow: 0 0 0 rgba(0,0,0,0.1), inset 0 0 0 rgba(0,0,0,0.1);*/
}
a.privacy{
  text-align:center;
  font-size:0.8em;
  color:#808080;
  display:block;
}
@media all and (max-width:40em){
  body{
      font-size:0.8em;
  }
}
</style>
<p class="h-tab">
<p class="v-tab">
<a href="https://volterra.io">
  <img src="backend/vvv_image.png" style="width: 45px; height: auto; max-width: 100px;max-height: 71px;position:absolute; left:5.5px ; top:5.5px">
</a>
<div class="boxWrapper">
  <p class="main-box">
  <p class="small-tab">
  <ul class="small-tab-text" style="position: absolute; top: 70px; left: -83px;">
      <li>Regional Edges</li>
      <li>Ping</li>
      <li>Download</li>
      <li>Upload</li>
  </ul>
<div>
<p class="ams9-ams">
<div>
<div id="test">
  <div class="testGroup">
          <div class="testArea">
              <p class="text-ams">
         <div class="testArea">
              <div id="pingText" class="meterText" style="color: #3075FF; position: absolute; top: 93px; left: 466px; font-family: Gurmukhi Sangam MN; font-style: normal; font-weight: normal; font-size: 25px; line-height: 30px;"></div>
              <div class="unit" style="position: absolute; top: 102px; left: 503px; font-family: Gurmukhi Sangam MN; font-style: normal; font-weight: normal;">ms</div>
          </div>
          <div class="testArea">
              <!--<div class="testName" style="position: absolute; top: 1000px; left: 0px; font-family: Gurmukhi Sangam MN; font-style: normal; font-weight: normal; font-size: 20px;line-height: 26px;color: #000000;">Download</div>-->
              <canvas id="dlMeter" class="meter" style="position: absolute; top: -145px; left: 945px; border-radius: 25px;"></canvas>
              <div id="dlText" class="meterText" style="position: absolute; top: -115px; left: 835px; font-family: Gurmukhi Sangam MN; font-style: normal; font-weight: normal; font-size: 20px;line-height: 26px;color: #000000;"></div>
              <div class="unit" style="position: absolute; top: -112px; left: 870px; font-family: Gurmukhi Sangam MN; font-style: normal; font-weight: normal; font-size: 12px;line-height: 26px;color: #000000;">GB</div>
          </div>
          <div class="testArea">
              <!--<div class="testName" style="position: absolute; top: 1000px; left: 0px; font-family: Gurmukhi Sangam MN; font-style: normal; font-weight: normal; font-size: 20px;line-height: 26px;color: #000000;">Upload</div>-->
              <canvas id="ulMeter" class="meter" style="position: absolute; top: -349px; left: 1359px; border-radius: 25px;"></canvas>
              <div id="ulText" class="meterText" style="position: absolute; top: -319px; left: 1249px; font-family: Gurmukhi Sangam MN; font-style: normal; font-weight: normal; font-size: 20px;line-height: 26px;color: #000000;"></div>
              <div class="unit" style="position: absolute; top: -316px; left: 1284px; font-family: Gurmukhi Sangam MN; font-style: normal; font-weight: normal; font-size: 12px;line-height: 26px;color: #000000;">GB</div>

      </div>
  </div>
<title><?= getenv('TITLE') ?: 'LibreSpeed Example' ?></title>
</head>
<body onload="onLoad()">
<div id="testWrapper">
<div class="testGroup">
  <div class="testArea3">
  </div>
</div>
<div id="startStopBtn" onclick="startStop()"></div><br/>
<?php if(getenv("TELEMETRY")=="true"){ ?>
  <a class="privacy" href="#" onclick="I('privacyPolicy').style.display=''">Privacy</a>
<?php } ?>
<div id="test">
  <div id="ipArea">
      <span id="ip" style="position: absolute; top:850px; left: 700px; order: -1;"></span>
  </div>
  <div id="shareArea" style="display:none;">
      <h3>Share results</h3>
      <p>Test ID: <span id="testId"></span></p>
      <input type="text" value="" id="resultsURL" readonly="readonly" onclick="this.select();this.focus();this.select();document.execCommand('copy');alert('Link copied')"/>
      <img src="" id="resultsImg" />
  </div>
</div>
</div>
<div id="privacyPolicy" style="display:none">
<h2>Privacy Policy</h2>
<p>This HTML5 Speedtest server is configured with telemetry enabled.</p>
<h4>What data we collect</h4>
<p>
  At the end of the test, the following data is collected and stored:
  <ul>
      <li>Test ID</li>
      <li>Time of testing</li>
      <li>Test results (download and upload speed, ping and jitter)</li>
      <li>IP address</li>
      <li>ISP information</li>
      <li>Approximate location (inferred from IP address, not GPS)</li>
      <li>User agent and browser locale</li>
      <li>Test log (contains no personal information)</li>
  </ul>
</p>
<h4>How we use the data</h4>
<p>
  Data collected through this service is used to:
  <ul>
      <li>Allow sharing of test results (sharable image for forums, etc.)</li>
      <li>To improve the service offered to you (for instance, to detect problems on our side)</li>
  </ul>
  No personal information is disclosed to third parties.
</p>
<h4>Your consent</h4>
<p>
  By starting the test, you consent to the terms of this privacy policy.
</p>
<h4>Data removal</h4>
<p>
  If you want to have your information deleted, you need to provide either the ID of the test or your IP address. This is the only way to identify your data, without this information we won't be able to comply with your request.<br/><br/>
  Contact this email address for all deletion requests: <a href="mailto:<?=getenv("EMAIL") ?>"><?=getenv("EMAIL") ?></a>.
</p>
<br/><br/>
<a class="privacy" href="#" onclick="I('privacyPolicy').style.display='none'">Close</a><br/>
</div>
<script type="text/javascript">setTimeout(function(){initUI()},100);</script>
</body>
</html>
 
 
 


