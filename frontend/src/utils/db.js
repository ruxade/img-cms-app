import { openDB } from 'idb';

const DB_NAME = 'images-db';
const DB_VERSION = 1;
const STORE_NAME = 'images';


export async function initDB() {
  return openDB(DB_NAME, DB_VERSION, {
    upgrade(db) {
      if (!db.objectStoreNames.contains(STORE_NAME)) {
        // Create an object store for images using "id" as the key.
        db.createObjectStore(STORE_NAME, { keyPath: 'id' });
      }
    },
  });
}

export async function saveImages(images) {
  const db = await initDB();
  const tx = db.transaction(STORE_NAME, 'readwrite');
  for (const image of images) {
      const plainImage = {
      id: image.id,
      caption: image.caption,
      path: image.path,
      visibility: image.visibility,
    };
    await tx.store.put(plainImage);
  }
  await tx.done;
  console.log('saved- indexedDB')
}

export async function getOfflineImages() {
  const db = await initDB();
  return db.getAll(STORE_NAME);
}

export async function clearImages() {
  const db = await initDB();
  const tx = db.transaction(STORE_NAME, 'readwrite');
  await tx.store.clear();
  await tx.done;
}
