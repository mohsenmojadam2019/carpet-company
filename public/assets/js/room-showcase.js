(()=>{
  const root=document.querySelector('[data-room-selector]');
  const dataNode=document.getElementById('room-showcase-data');
  if(!root||!dataNode)return;

  let groups=[];
  try{groups=JSON.parse(dataNode.textContent||'[]')}catch{return}
  if(!Array.isArray(groups)||!groups.length)return;

  const rug=root.querySelector('[data-room-rug]');
  const name=root.querySelector('[data-room-name]');
  const price=root.querySelector('[data-room-price]');
  const categoryLabel=root.querySelector('[data-room-category]');
  const productLink=root.querySelector('[data-room-product-link]');
  const tabs=[...document.querySelectorAll('[data-category-index]')];
  const track=document.querySelector('[data-product-track]');
  const viewport=document.querySelector('.product-slider-viewport');
  const prev=document.querySelector('[data-slider-prev]');
  const next=document.querySelector('[data-slider-next]');
  const currentNumber=document.querySelector('[data-current-number]');
  const totalNumber=document.querySelector('[data-total-number]');
  if(!rug||!track||!viewport||!prev||!next)return;

  let categoryIndex=0;
  let productIndex=0;
  let swapTimer=null;
  const pad=n=>String(n).padStart(2,'0');

  const currentGroup=()=>groups[categoryIndex]||groups[0];
  const currentProducts=()=>currentGroup()?.products||[];

  function preload(products){
    products.forEach(item=>{if(item.image){const img=new Image();img.decoding='async';img.src=item.image}});
  }

  function updateRoom(animate=true){
    const group=currentGroup();
    const products=currentProducts();
    if(!group||!products.length)return;
    productIndex=Math.max(0,Math.min(productIndex,products.length-1));
    const item=products[productIndex];

    const apply=()=>{
      rug.src=item.image;
      rug.alt=item.name||'فرش انتخاب‌شده';
      if(name)name.textContent=item.name||'';
      if(price)price.textContent=item.price||'';
      if(categoryLabel)categoryLabel.textContent=group.name||'';
      if(productLink)productLink.href=item.url||'#';
      if(currentNumber)currentNumber.textContent=pad(productIndex+1);
      if(totalNumber)totalNumber.textContent=pad(products.length);
      [...track.children].forEach((card,index)=>{
        const active=index===productIndex;
        card.classList.toggle('is-active',active);
        card.setAttribute('aria-pressed',active?'true':'false');
      });
      const activeCard=track.children[productIndex];
      if(activeCard){
        const left=activeCard.offsetLeft-(viewport.clientWidth-activeCard.offsetWidth)/2;
        viewport.scrollTo({left,behavior:animate?'smooth':'auto'});
      }
      requestAnimationFrame(()=>rug.classList.remove('is-changing'));
    };

    clearTimeout(swapTimer);
    if(animate){rug.classList.add('is-changing');swapTimer=setTimeout(apply,150)}else{apply()}
  }

  function renderProducts(){
    const group=currentGroup();
    const products=currentProducts();
    track.innerHTML='';
    track.style.direction='ltr';
    preload(products.slice(0,6));

    products.forEach((item,index)=>{
      const button=document.createElement('button');
      button.type='button';
      button.className='product-choice'+(index===productIndex?' is-active':'');
      button.dataset.productIndex=String(index);
      button.setAttribute('aria-label',`نمایش ${item.name} در اتاق`);
      button.setAttribute('aria-pressed',index===productIndex?'true':'false');
      button.style.direction='rtl';
      button.innerHTML=`<img src="${item.image}" width="190" height="148" alt="" loading="${index<3?'eager':'lazy'}"><small>${group.name}</small><b></b><span></span>`;
      button.querySelector('b').textContent=item.name||'';
      button.querySelector('span').textContent=item.price||'';
      button.addEventListener('click',()=>{productIndex=index;updateRoom()});
      track.appendChild(button);
    });
    updateRoom(false);
  }

  function selectCategory(index){
    if(!groups[index])return;
    categoryIndex=index;
    productIndex=0;
    tabs.forEach((tab,i)=>{
      const active=i===categoryIndex;
      tab.classList.toggle('is-active',active);
      tab.setAttribute('aria-selected',active?'true':'false');
    });
    renderProducts();
  }

  function move(step){
    const products=currentProducts();
    if(!products.length)return;
    productIndex=(productIndex+step+products.length)%products.length;
    updateRoom();
  }

  tabs.forEach((tab,index)=>tab.addEventListener('click',()=>selectCategory(index)));
  prev.addEventListener('click',()=>move(-1));
  next.addEventListener('click',()=>move(1));
  root.addEventListener('keydown',event=>{
    if(event.key==='ArrowLeft'){event.preventDefault();move(1)}
    if(event.key==='ArrowRight'){event.preventDefault();move(-1)}
  });

  let startX=null;
  [root,viewport].forEach(target=>{
    target.addEventListener('pointerdown',event=>{if(event.pointerType!=='mouse')startX=event.clientX},{passive:true});
    target.addEventListener('pointerup',event=>{
      if(startX===null)return;
      const delta=event.clientX-startX;
      startX=null;
      if(Math.abs(delta)>42)move(delta<0?1:-1);
    },{passive:true});
  });

  renderProducts();
})();
