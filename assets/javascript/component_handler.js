/*

*/

const component_div_prefix = '<div>';
const component_div_sufix = '</div>';

/* Adds component user clicked on to the canvas */
function addComponentOnUserClick( imgPath) {
	
	var containerElement = document.getElementById('canvas_components_container');
	
	// in order to move components by click, not by "drag&drop" style, uncomment this
	//containerElement.innerHTML = containerElement.innerHTML + component_div_prefix + '<img src="'+ imgPath +'" class="drag" onclick="OnMouseClick()" onMouseClick="OnMouseClick()" />' + component_div_sufix;
	containerElement.innerHTML = containerElement.innerHTML + component_div_prefix + '<img src="'+ imgPath +'" class="drag" onmousedown="OnMouseClickDown()" onmouseup="OnMouseClickUp()" />' + component_div_sufix;
	//alert();
}