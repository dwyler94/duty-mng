export const isMobile = () => {
    return window.innerWidth < 768;
}

export const scrollToTop = () => {
    window.scrollTo({
        behavior:'smooth',
        top: 0
    });
}
