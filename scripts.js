function showIframe(frameName) {
    const frames = ['dataFrame', 'searchFrame', 'recommendFrame', '01Frame', 'cFrame'];
    // 隱藏所有 iframe

    frames.forEach(frame => {
        const iframe = document.getElementById(frame);
        if (iframe) {
            
            iframe.style.display = 'none';

        }
        if (frame === frameName) {
            iframe.src = iframe.src; // 重新加载当前 iframe
        }
    });

    // 顯示指定的 iframe
    const targetFrame = document.getElementById(frameName);
    if (targetFrame) {
        targetFrame.style.display = 'block';
    } else {
        console.error('指定的 iframe 不存在:', frameName);
    }
}

function submitSearchForm() {
    const form = document.getElementById('searchForm');
    form.submit();
    showIframe('searchFrame');
}

