async function api(path, method='GET', body=null) 
{
    const opts = { method, credentials: 'same-origin' };
    if (body) 
    { 
        opts.headers = {'Content-Type':'application/json'}; opts.body = JSON.stringify(body); 
    }

    const res = await fetch('/luokkavaraus/api/' + path, opts);
    return res.json();
}
async function init() 
{
    const session = await api('get_session.php');
    if (!session) 
    {
        document.getElementById('login-view').style.display = 'block';
        document.getElementById('main-view').style.display = 'none';
    } 
    else 
    {
        document.getElementById('login-view').style.display = 'none';
        document.getElementById('main-view').style.display = 'block';
        document.getElementById('userName').textContent = session.full_name;
        document.getElementById('userRole').textContent = session.role;
        loadClassrooms(); loadReservations();
    }
}
document.getElementById('loginBtn').addEventListener('click', async ()=>
{
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const res = await fetch('/luokkavaraus/api/login.php', {method:'POST', body: new URLSearchParams({email,password})});
    const data = await res.json();

    if (data.status === 'success') location.reload();

    else document.getElementById('loginMsg').textContent = data.message || 'Virhe';
});

document.getElementById('logoutBtn').addEventListener('click', async ()=>
{ 
    await api('logout.php','POST'); location.reload(); 
});

async function loadClassrooms()
{
    const cls = await api('classrooms.php');
    const sel = document.getElementById('classroomSelect'); sel.innerHTML='';
    cls.forEach(c=>{ const opt = document.createElement('option'); opt.value=c.id; opt.textContent = c.name + ' ('+c.location+')'; sel.appendChild(opt); });
}

async function loadReservations()
{
    const res = await api('reservations.php');
    const tbody = document.querySelector('#reservationsTable tbody'); tbody.innerHTML='';
    res.forEach(r=>{
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${r.classroom_name}</td>
                        <td>${r.user_name}</td>
                        <td>${r.start_time}</td>
                        <td>${r.end_time}</td>
                        <td>${r.status}</td>
                        <td><button onclick=editReservation('${r.id}')>Muokkaa</button>
                        <button onclick=deleteReservation('${r.id}')>Poista</button></td>`;
        tbody.appendChild(tr);
    });
}

document.getElementById('reserveForm').addEventListener('submit', async (e)=>
{
    e.preventDefault();
    const classroom_id = document.getElementById('classroomSelect').value;
    const start_time = document.getElementById('startTime').value.replace('T',' ');
    const end_time = document.getElementById('endTime').value.replace('T',' ');
    const purpose = document.getElementById('purpose').value;
    const res = await api('reservations.php','POST',{classroom_id,start_time,end_time,purpose});

    if (res.status === 'success') 
    { 
        document.getElementById('reserveMsg').textContent = 'Varaus luotu'; loadReservations(); 
    }

    else 
    { 
        document.getElementById('reserveMsg').textContent = res.error || 'Virhe'; 
    }
});

async function deleteReservation(id)
{
    if (!confirm('Haluatko poistaa varauksen?')) return;
    const res = await api('reservations.php','DELETE',{id});
    if (res.status === 'success') loadReservations(); else alert(res.error || 'Virhe');
}

function editReservation(id)
{
    const newStart = prompt('Uusi aloitusaika (YYYY-MM-DD HH:MM:SS)');
    const newEnd = prompt('Uusi loppumisaika (YYYY-MM-DD HH:MM:SS)');
    if (!newStart || !newEnd) return;
    api('reservations.php','PUT',{id,start_time:newStart,end_time:newEnd}).then(r=>{ if (r.status==='success') loadReservations(); else alert(r.error||'Virhe'); });
}

init();