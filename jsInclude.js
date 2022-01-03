
function OnLoadCallback(lastUpdate)
{
  reload();
  ReloadChat(lastUpdate);
}

function ShowCardDetail(e, that)
{
  ShowDetail(e, that.getElementsByTagName("IMG")[0].src);
}

function ShowDetail(e, imgSource)
{
  imgSource = imgSource.replace("SmallCardImages", "BigCardImages");
  var el = document.getElementById("cardDetail");
  el.innerHTML = "<img style='height:523px; width:375px;' src='" + imgSource + "' />"
  el.style.left = e.clientX < window.innerWidth / 2 ? e.clientX + 30 : e.clientX - 400;
  el.style.top = e.clientY > window.innerHeight / 2 ? e.clientY - 523 - 20 : e.clientY + 30;
  if(parseInt(el.style.top) + 523 >= window.innerHeight)
  {
    el.style.top = window.innerHeight - 530;
    el.style.left = e.clientX < window.innerWidth / 2 ? e.clientX + 30 : e.clientX - 400;
  }
  else if(parseInt(el.style.top) <= 0)
  {
    el.style.top = 5;
    el.style.left = e.clientX < window.innerWidth / 2 ? e.clientX + 30 : e.clientX - 400;
  }
  el.style.zIndex = 100;
  el.style.display = "inline";
}

function HideCardDetail()
{
  var el = document.getElementById("cardDetail");
  el.style.display = "none";
}

function ChatKey(event)
{
  if(event.keyCode === 13) 
  {
    event.preventDefault();
    SubmitChat();
  }
  event.stopPropagation();
}

function SubmitChat()
{
  var chatBox = document.getElementById("chatText");
  if(chatBox.value == "") return;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      //alert(this.responseText);
      //if(this.responseText[1] == "1") { location.reload(); }
    }
  }
  var ajaxLink = "SubmitChat.php?gameName=" + document.getElementById("gameName").value;
  ajaxLink += "&playerID=" + document.getElementById("playerID").value + "&chatText=" + encodeURI(chatBox.value);
  xmlhttp.open("GET", ajaxLink, true);
  xmlhttp.send();
  chatBox.value = "";
}


function ReloadChat(lastUpdate)
{
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      el = document.getElementById('gamelog');
      ReloadChat(this.responseText.substring(0, 11));
      var logText = this.responseText.slice(11);
      if(logText != "")
      {
        el.innerHTML = logText;
        el.scrollTop = el.scrollHeight;
      }
    }
  }
  var ajaxLink = "ReloadChat.php?gameName=" + document.getElementById("gameName").value;
  ajaxLink += "&playerID=" + document.getElementById("playerID").value + "&lastUpdate=" + lastUpdate;
  xmlhttp.open("GET", ajaxLink, true);
  xmlhttp.send();
}