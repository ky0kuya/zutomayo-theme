const fs = require('node:fs');
const vm = require('node:vm');
const assert = require('node:assert/strict');
const nodes = new Map();
const node = () => ({style: {}, children: [], value: '', innerText: '', dataset: {}, classList: {add(){},remove(){}}, addEventListener(){}, appendChild(child){this.children.push(child);}, get innerHTML(){return '';}, set innerHTML(value){if(value) throw Error('Unexpected HTML sink');}});
const rows = [node(), node()];
rows[0].dataset.cats = JSON.stringify(["quote'category", 'second']);
rows[1].dataset.cats = JSON.stringify(['other']);
const document = {
    getElementById(id) {if (!nodes.has(id)) nodes.set(id, node()); return nodes.get(id);},
    querySelectorAll(selector) {return selector === '.file-row' ? rows : [];},
    createTextNode(text) {return {type: 'text', text};},
    createElement(tag) {return {type: 'element', tag};}
};
const context = vm.createContext({document, window: {}, Audio: function(){}, console, setTimeout(){}, setInterval(){}});
vm.runInContext(fs.readFileSync(process.argv[2], 'utf8'), context);
const payload = '<img src=x onerror=globalThis.injected=1>';
context.printTerm(payload);
assert.deepEqual(nodes.get('term-output').children, [{type:'text',text:payload},{type:'element',tag:'br'}]);
context.filterCategory("quote'category");
assert.equal(rows[0].style.display, 'flex');
assert.equal(rows[1].style.display, 'none');
context.filterCategory('second');
assert.equal(rows[0].style.display, 'flex');
context.filterCategory('all');
assert.equal(rows[1].style.display, 'flex');
assert.equal(context.injected, undefined);
console.log('PASS: rendered JavaScript syntax, terminal text-only output and multiple-category filtering');
