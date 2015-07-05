var results='';
var sessionId=0;
var user_Id = 0;
var storage = chrome.storage.sync;
var history = chrome.history;

storage.get("userId", function(items) {
    if(items.userId!=0)
      user_Id = items.userId;
    else
      setUserId();
});

storage.get("sessionId", function(items) {
    sessionId = items.sessionId;
    setSessionId();
});

chrome.runtime.onStartup.addListener(function() {

});

var historyResult;
var transitionType;
chrome.history.onVisited.addListener(function(result) {
  historyResult=result;
  chrome.history.getVisits({url:result.url}, function(item){
    transitionType = item[item.length-1].transition;
  });
});

chrome.tabs.onCreated.addListener(function(tab) {
  storage.get("userId", function(items) {
    if(items.userId!=0)
      user_Id = items.userId;
    else
      setUserId();
  });
  if (user_Id!=0) {
    if (tab.url!='chrome://newtab/') {
      var data = "queryType=created&userId="+user_Id+"&sessionId="+sessionId+"&tabId="+tab.id+"&tabUrl="+tab.url+"&tabTitle="+tab.title+"&visitCount="+historyResult.visitCount+"&typedCount="+historyResult.typedCount+"&transitionType="+transitionType; 
      submitData(data);
    }  
  }
});


chrome.tabs.onUpdated.addListener(function(tabId, changeInfo, tab) {
  storage.get("userId", function(items) {
    if(items.userId!=0)
      user_Id = items.userId;
    else
      setUserId();
  });
  if(changeInfo.status == 'complete'){
    if (tab.url!='chrome://newtab/') {
      if (user_Id!=0) {
        var data = "queryType=updated&userId="+user_Id+"&sessionId="+sessionId+"&tabId="+tabId+"&tabUrl="+tab.url+"&tabTitle="+tab.title+"&visitCount="+historyResult.visitCount+"&typedCount="+historyResult.typedCount+"&transitionType="+transitionType; 
        submitData(data);
      }
    }
  } 
});

chrome.tabs.onRemoved.addListener(function(tabId, removeInfo) {
  storage.get("userId", function(items) {
    if(items.userId!=0)
      user_Id = items.userId;
    else
      setUserId();
  });
  if (user_Id) {
    var data = "queryType=removed&userId="+user_Id+"&sessionId="+sessionId+"&tabId="+tabId;
    submitData(data);
  }
});

function getNewUserId(){
  var xmlhttp;
  if (window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){
    if (xmlhttp.readyState==4 && xmlhttp.status==200){
      user_Id = xmlhttp.responseText;
      if(user_Id!=0)
        return user_Id;
      //else
        //window.open("http://www.spgoyal.in/projects/snwccb/login.php");
        //chrome.tabs.create({url:"http://www.spgoyal.in/projects/snwccb/login.php"}, function(){});
    }
  }
  xmlhttp.open("GET","http://www.spgoyal.in/projects/snwccb/index.php?query=getUid",false);
  xmlhttp.send();
  //return user_Id;
  storage.set({'userId': user_Id}, function(){});
}

function setUserId(){
    getNewUserId();
    
    //storage.set({'userId': user_Id}, function(){
      //  alert(user_Id);
    //});
    
}

function setSessionId(){
    sessionId++;
    storage.set({'sessionId': sessionId}, function(){
    });
}


function submitData(data){
  var xmlhttp;
  if (window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){
    if (xmlhttp.readyState==4 && xmlhttp.status==200){
      //console.log(xmlhttp.responseText);
    }
  }
  xmlhttp.open("GET","http://www.spgoyal.in/projects/snwccb/index.php?"+data,true);
  xmlhttp.send();
}

chrome.browserAction.onClicked.addListener(function() {
  chrome.tabs.create({url:"http://www.spgoyal.in/projects/snwccb/login.php"}, function(){
    getNewUserId();
  });

});

chrome.runtime.onInstalled.addListener("install", function() {
  getNewUserId();
  //window.open("http://www.spgoyal.in/projects/snwccb/login.php").focus();
});

chrome.management.onInstalled.addListener(function(info) {
  getNewUserId();
  //window.open("http://www.spgoyal.in/projects/snwccb/login.php");
  //chrome.windows.create({url:"http://www.spgoyal.in/projects/snwccb/login.php"}, function(){});
  chrome.tabs.create({url:"http://www.spgoyal.in/projects/snwccb/login.php"}, function(){
  });
});