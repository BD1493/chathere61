<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Private Chat</title><link rel="stylesheet" href="style.css"></head>
<body>
<div style="width:420px;margin:30px auto;background:#071018;padding:20px;border-radius:12px">
  <h2 style="margin-top:0;color:#e6eef6">Private Chat</h2>
  <div style="margin-top:12px;">
    <button onclick="showCreate()" class="send-btn">Create Room</button>
    <button onclick="showJoin()" class="send-btn" style="margin-left:8px">Join Room</button>
  </div>
  <div id="createBox" style="display:none;margin-top:12px;">
    <input id="createName" placeholder="Your name" style="width:100%;padding:10px;border-radius:8px"><br><br>
    <input id="createSubject" placeholder="Subject (optional)" style="width:100%;padding:10px;border-radius:8px"><br><br>
    <input id="createPassword" placeholder="Room password (optional)" type="password" style="width:100%;padding:10px;border-radius:8px"><br><br>
    <button onclick="createRoom()" class="send-btn">Create & Open</button>
  </div>
  <div id="joinBox" style="display:none;margin-top:12px;">
    <input id="joinName" placeholder="Your name" style="width:100%;padding:10px;border-radius:8px"><br><br>
    <input id="joinCode" placeholder="Room code" style="width:100%;padding:10px;border-radius:8px"><br><br>
    <input id="joinPassword" placeholder="Room password (if required)" type="password" style="width:100%;padding:10px;border-radius:8px"><br><br>
    <button onclick="joinRoom()" class="send-btn">Join Room</button>
  </div>
</div>
<script>
function showCreate(){document.getElementById('createBox').style.display='block';document.getElementById('joinBox').style.display='none'}
function showJoin(){document.getElementById('joinBox').style.display='block';document.getElementById('createBox').style.display='none'}
async function createRoom(){
  const name=document.getElementById('createName').value.trim(); const subject=document.getElementById('createSubject').value.trim();
  const password=document.getElementById('createPassword').value||'';
  if(!name){alert('Enter your name');return;}
  const res=await fetch('api/create_room.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({user:name,subject,password})});
  const js=await res.json();
  if(js.success){ localStorage.setItem('chatUser',name); localStorage.setItem('hostToken_'+js.code, js.host_token); window.location='chat.php?room='+js.code+'&subject='+encodeURIComponent(subject);} else {alert('Failed to create room');}
}
async function joinRoom(){
  const name=document.getElementById('joinName').value.trim(); const code=document.getElementById('joinCode').value.trim().toUpperCase(); const password=document.getElementById('joinPassword').value||'';
  if(!name||!code){alert('Enter name and code');return;}
  const res=await fetch('api/join_room.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:new URLSearchParams({user:name,room:code,password})});
  const js=await res.json();
  if(js.success){localStorage.setItem('chatUser',name);window.location='chat.php?room='+code;} else { if(js.error==='password_required') alert('This room requires a password.'); else if(js.error==='invalid_password') alert('Incorrect room password.'); else alert('Room not found'); }
}
</script>
</body>
</html>
