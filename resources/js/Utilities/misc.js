export const Random = {};
Random.string = (length = 5) => Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, length);
Random.int = (min, max) => Math.floor(Math.random() * (max - min + 1)) + Math.floor(min);
Random.float = (min, max) => Math.random() * (max - min) + min;
Random.fromArray = (arr) => arr[Math.floor(Math.random() * arr.length)];