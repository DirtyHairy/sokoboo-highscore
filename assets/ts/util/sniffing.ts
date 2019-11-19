export const IS_FIREFOX = !!window.navigator.userAgent.match(/firefox/i);
export const NO_HOVER = !!window.matchMedia && window.matchMedia('not all and (hover: hover)').matches;
