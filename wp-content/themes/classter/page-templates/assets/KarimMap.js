const addTerm = () => {
    const newNode = document.querySelector(".term").cloneNode(true);
    document.querySelector(".terms-wrap").appendChild(newNode);
}
const removeTerm = btn => btn.closest(".term").remove();