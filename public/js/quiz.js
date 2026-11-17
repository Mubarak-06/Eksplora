(() => {
    const root = document.querySelector('#game');
    if (!root) return;
    const status = document.querySelector('#game-status'), content = document.querySelector('#game-content');
    let game, index = 0, selected = null, tiles = [], moves = [], busy = false;
    const el = (tag, text, cls) => { const n = document.createElement(tag); if (text !== undefined) n.textContent = text; if (cls) n.className = cls; return n; };
    const button = (label, fn, cls='button') => { const n=el('button',label,cls); n.type='button'; n.addEventListener('click',fn); return n; };
    const error = e => { status.textContent = e.message; status.className='notice error'; };
    const post = async (url, data={}) => {
        const response = await fetch(url, {method:'POST', credentials:'same-origin', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify(data)});
        const body = await response.json().catch(()=>({message:'Server tidak merespons. Coba lagi.'}));
        if (!response.ok) throw new Error(Object.values(body.errors || {}).flat().join(' ') || body.message || 'Permintaan gagal.');
        return body;
    };
    const finish = async () => {
        if (busy) return; busy = true; status.textContent='Menghitung hasil…';
        try {
            const result = await post(root.dataset.finish,{token:game.token,moves});
            content.replaceChildren(el('div','🏆','result-icon'),el('h2','Kuis Selesai!'),el('p',result.score+'/100','score'));
            content.append(el('p',result.saved ? 'Hasil tersimpan. Tambahan poin: '+result.points+'.' : 'Kamu bermain sebagai tamu. Masuk sebelum memulai kuis berikutnya agar hasil dapat disimpan.'));
            const retry=el('a','Ulangi Permainan','button');retry.href=root.dataset.retry;content.append(retry);
            const link=el('a',result.saved?'Lihat Koleksi':'Masuk Akun','button secondary');link.href=result.saved?root.dataset.collection:root.dataset.login;content.append(link);
            status.textContent='Terima kasih sudah belajar!';
        } catch(e) { error(e); content.append(button('Coba Simpan Lagi',finish)); }
        finally { busy=false; }
    };
    const renderQuestion = () => {
        const q=game.questions[index]; content.replaceChildren(); selected=null;
        status.textContent='Soal '+(index+1)+' dari '+game.questions.length;
        const progress=el('progress');progress.max=game.questions.length;progress.value=index;progress.setAttribute('aria-label','Progres kuis'); content.append(progress);
        const heading=el('h2',q.question);heading.tabIndex=-1;content.append(heading);
        if(q.image) { const img=el('img',undefined,'question-image');img.src=q.image;img.alt='Ilustrasi organisme untuk soal ini';content.append(img); }
        const choices=el('div',undefined,'choices');
        q.options.forEach((o,i)=>{
            const b=button(String.fromCharCode(65+i)+'. '+o.label,()=>{
                selected=o.value; choices.querySelectorAll('button').forEach(n=>{n.classList.remove('chosen');n.setAttribute('aria-pressed','false');});
                b.classList.add('chosen');b.setAttribute('aria-pressed','true');check.disabled=false;
            },'choice');b.setAttribute('aria-pressed','false');choices.append(b);
        });
        content.append(choices);
        const check=button('Periksa Jawaban',async()=>{
            if (busy || selected===null) return;busy=true;check.disabled=true;
            try {
                const f=await post(root.dataset.answer,{token:game.token,index,answer:selected});
                choices.querySelectorAll('button').forEach(n=>n.disabled=true);check.remove();
                const feedback=el('div',undefined,'notice '+(f.correct?'':'error'));feedback.setAttribute('role','status');
                feedback.append(el('strong',f.correct?'✓ Benar!':'Mari belajar lagi.'),el('p','Jawaban yang sesuai: '+f.answer),el('p',f.explanation));content.append(feedback);
                content.append(button(index+1===game.questions.length?'Lihat Nilai':'Soal Berikutnya',()=>{index++;if(index===game.questions.length) finish();else renderQuestion();}));
            } catch(e) { error(e);check.disabled=false; }
            finally {busy=false;}
        });check.disabled=true;content.append(check);heading.focus();
    };
    const renderPuzzle = () => {
        content.replaceChildren();status.textContent='Pilih dua potongan untuk menukar. Gerakan: '+moves.length+'/500';
        const layout=el('div',undefined,'puzzle-layout');const board=el('div',undefined,'puzzle-board');
        tiles.forEach((tile,i)=>{
            const b=button('',()=>{
                if(busy) return;
                if(selected===null) {selected=i;b.classList.add('chosen');b.setAttribute('aria-pressed','true');}
                else if(selected===i) {selected=null;renderPuzzle();}
                else {const a=selected;[tiles[a],tiles[i]]=[tiles[i],tiles[a]];moves.push([a,i]);selected=null;renderPuzzle();if(tiles.every((v,k)=>v===k)) finish();}
            },'puzzle-tile');
            b.style.backgroundImage='url("'+game.image+'")';b.style.backgroundPosition=(tile%3)*50+'% '+Math.floor(tile/3)*50+'%';
            b.setAttribute('aria-label','Posisi '+(i+1)+', potongan '+(tile+1));b.setAttribute('aria-pressed','false');b.disabled=moves.length>=500;board.append(b);
        });
        const sample=el('div');sample.append(el('h3','Gambar Contoh'));const img=el('img',undefined,'puzzle-example');img.src=game.image;img.alt='Gambar utuh sebagai contoh susunan';sample.append(img);
        layout.append(board,sample);content.append(layout);
        content.append(button('Reset Puzzle',()=>{tiles=[...game.tiles];moves=[];selected=null;renderPuzzle();},'button secondary'));
        if(moves.length>=500) status.textContent='Batas gerakan tercapai. Gunakan Reset Puzzle untuk mencoba lagi.';
    };
    document.querySelector('#game-start').addEventListener('click',async e=>{
        const start=e.currentTarget;start.disabled=true;status.textContent='Menyiapkan permainan…';
        try {game=await post(root.dataset.start);start.remove();if(game.type==='puzzle') {tiles=[...game.tiles];renderPuzzle();}else renderQuestion();}
        catch(err){error(err);start.disabled=false;}
    });
})();
