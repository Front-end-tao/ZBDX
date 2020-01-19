export default {
  set: (n, val) => {
    if (window.localStorage && localStorage.setItem) {
      try {
        localStorage.setItem(n, val);
      } catch (e) {
        Utils.cookieStore.set(n, val);
      }
    } else {
      Utils.cookieStore.set(n, val);
    }
    return this;
  },
  get: (n) => {
    if (window.localStorage && localStorage.getItem) {
      try {
        return localStorage.getItem(n);
      } catch (e) {
        return Utils.cookieStore.get(n);
      }
    } else {
      return Utils.cookieStore.get(n);
    }
  },
  remove: (n) => {
    if (window.localStorage && localStorage.removeItem) {
      try {
        localStorage.removeItem(n);
      } catch (e) {
        Utils.cookieStore.unset(n);
      }
    } else {
      Utils.cookieStore.unset(n);
    }
    return this;
  }
}