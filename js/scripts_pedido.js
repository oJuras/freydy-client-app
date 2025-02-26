let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
function adicionarCarrinho(idItem, nomeItem, preco) {
    carrinho.push({ id: idItem, nome: nomeItem, preco: preco });
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    atualizarCarrinho();
    atualizarTotal();
    if (!document.getElementById('menu-carrinho').classList.contains('open')) {
        toggleMenu(); 
    }
}

function atualizarCarrinho() {
    const carrinhoLista = document.getElementById('carrinho-lista');
    const numeroItens = document.getElementById('numero-itens');
    carrinhoLista.innerHTML = '';
    carrinho.forEach((item, index) => {
        const li = document.createElement('li');
        li.innerHTML = `${item.nome} - R$ ${item.preco.toFixed(2).replace('.', ',')}
        <button class="remover-item" onclick="removerItemCarrinho(${index})">X</button>`;
        carrinhoLista.appendChild(li);
    });
    numeroItens.textContent = carrinho.length;
}

function atualizarTotal() {
    const total = carrinho.reduce((sum, item) => sum + item.preco, 0);
    document.getElementById('total').textContent = total.toFixed(2).replace('.', ',');
}

function toggleMenu() {
    const menuCarrinho = document.getElementById('menu-carrinho');
    menuCarrinho.classList.toggle('open');
}

function limparCarrinho() {
    carrinho = [];
    localStorage.removeItem('carrinho');
    atualizarCarrinho();
    atualizarTotal();
    toggleMenu();
}

function removerItemCarrinho(index) {
    carrinho.splice(index, 1);
    localStorage.setItem('carrinho', JSON.stringify(carrinho));
    atualizarCarrinho();
    atualizarTotal();
}
