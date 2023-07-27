import axios from 'axios';

export default function useProduct(productId) {
    let product = null;
    axios.get('/product/' + productId + '/full')
    .then(response => {
        product = response.data;
    })

    return {product};
}