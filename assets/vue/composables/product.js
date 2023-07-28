import axios from 'axios';

export default async function useProduct(productId) {
    let response = await axios.get('/product/' + productId + '/full')
    let product = response.data;

    return {product};
}