const data = [
  {title:"Pogung Asri — Putri", area:"Pogung • 6 menit FT UGM", price:"Rp1.350.000", img:"https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=800&auto=format&fit=crop", meta:["Putri","Mandi dalam","Dapur"], desc:"Jendela tinggi, sirkulasi adem. Parkir motor luas, ibu kos ramah.", tall:true},
  {title:"Kost Santren Ceria", area:"Jakal Km 5 • 9 menit FMIPA", price:"Rp950.000", img:"https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?q=80&w=800&auto=format&fit=crop", meta:["Campur","Kost harian bisa"], desc:"Harga jujur tanpa biaya kebersihan tersembunyi.", tall:false},
  {title:"Omah Sleman Loft", area:"Condongcatur • 11 menit UPN", price:"Rp1.800.000", img:"https://images.unsplash.com/photo-1484154218962-a197022b5858?q=80&w=800&auto=format&fit=crop", meta:["Putra","AC","Rooftop"], desc:"Loft kayu, cahaya pagi masuk penuh. Cocok WFH.", tall:true},
  {title:"Griya Tebet Dalam", area:"Tebet • 5 menit St. Tebet", price:"Rp2.100.000", img:"https://images.unsplash.com/photo-1493809842364-78817add58d1?q=80&w=800&auto=format&fit=crop", meta:["Putri","Kamar mandi dalam","Laundry"], desc:"Akses TransJakarta, dapur bersama rapi.", tall:false},
  {title:"Kost Braga Tengah", area:"Braga • 8 menit ITB", price:"Rp1.250.000", img:"https://images.unsplash.com/photo-1502005229762-c367628952d4?q=80&w=800&auto=format&fit=crop", meta:["Campur","Balkon"], desc:"Balkon menghadap gang seni, kafe di bawah kos.", tall:false},
  {title:"Pondok Dipatiukur", area:"Dipatiukur • 4 menit Unpad", price:"Rp1.600.000", img:"https://images.unsplash.com/photo-1519710164239-da123dc03ef4?q=80&w=800&auto=format&fit=crop", meta:["Putri","Mandi dalam","Dapur"], desc:"Baru renov, tegel hijau, foto sesuai asli.", tall:true},
];

const grid = document.getElementById('kosGrid');
const sortSel = document.getElementById('sort');
function render(list){
  grid.innerHTML = list.map(k => `
    <article class="kos-card ${k.tall?'tall':''}">
      <img src="${k.img}" alt="${k.title}" loading="lazy" />
      <div class="kos-body">
        <div class="kos-meta">${k.meta.map(m=>`<span>${m}</span>`).join('')}<span>${k.area}</span></div>
        <h3>${k.title}</h3>
        <p class="kos-desc">${k.desc}</p>
        <div class="kos-foot">
          <div class="kos-price">${k.price} <small>/ bulan</small></div>
          <a class="btn solid" href="#" onclick="event.preventDefault();alert('Chat pemilik: menghubungkan WhatsApp ${k.title}')">Chat</a>
        </div>
      </div>
    </article>
  `).join('');
}
render(data);

sortSel?.addEventListener('change', () => {
  const v = sortSel.value;
  let out = [...data];
  if(v.includes('Harga')) out.sort((a,b)=> parseInt(a.price.replace(/\D/g,'')) - parseInt(b.price.replace(/\D/g,'')));
  if(v.includes('Rating')) out.reverse();
  render(out);
});

document.querySelectorAll('.chip').forEach(c=>{
  c.addEventListener('click', ()=> c.classList.toggle('is-on'));
});

// mobile menu simple toggle
const menu = document.querySelector('.menu');
const navLinks = document.querySelector('.nav-links');
const navCta = document.querySelector('.nav-cta');
let open=false;
menu?.addEventListener('click', ()=>{
  open=!open;
  menu.setAttribute('aria-expanded', String(open));
  const d = open?'flex':'none';
  navLinks.style.display=d; navCta.style.display=d;
  if(open){ navLinks.style.flexDirection='column'; navLinks.style.position='absolute'; navLinks.style.top='64px'; navLinks.style.left='0'; navLinks.style.right='0'; navLinks.style.background='var(--paper)'; navLinks.style.borderBottom='2px solid var(--ink)'; navLinks.style.padding='12px 24px'; navCta.style.position='absolute'; navCta.style.top='170px'; navCta.style.left='0'; navCta.style.right='0'; navCta.style.padding='0 24px 12px'; navCta.style.background='var(--paper)';}
});

// map pin interaction
document.querySelectorAll('.map-pin').forEach(p=>{
  p.style.cursor='pointer';
  p.addEventListener('click', ()=>{
    document.querySelectorAll('.map-pin').forEach(x=>x.classList.remove('is-active'));
    p.classList.add('is-active');
    p.style.background='var(--mustard)';
  });
});
