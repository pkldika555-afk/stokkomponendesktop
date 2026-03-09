const { contextBridge } = require('electron');

// Expose versi app ke renderer jika diperlukan
contextBridge.exposeInMainWorld('electronApp', {
  version: process.env.npm_package_version || '1.0.0',
});
