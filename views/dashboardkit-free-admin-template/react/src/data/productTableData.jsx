// project import
import { getImageUrl, ImagePath } from 'utils/getImageUrl';
import { NEW_PRODUCTS } from '../config/constant';
const GetAvatar = (name) => {
  const photo_new = `${name}.png`;
  return <img src={getImageUrl(`${photo_new}`, ImagePath.WIDGET)} alt="" className="img-20" />;
};

// Product table data
const ProductData = {
  wrapclass: 'table-card feed-card',
  height: '255px',
  title: 'Nuevos Productos',
  tableheading: ['Nombre', 'Imagen', 'Stock', 'Precio', 'Acción'],
  rowdata: NEW_PRODUCTS.map(newProduct => ({
    name: newProduct.name,
    image: <img src={newProduct.sources[0]} className="img-20" />,
    get status() {
      if (newProduct.stock >= 5) {
        return {
          badge: 'light-success',
          label: 'Disponible',
        };
      }

      if (newProduct.stock > 0) {
        return {
          badge: 'light-warning',
          label: 'Casi agotado',
        };
      }

      return {
        badge: 'light-danger',
        label: 'Agotado',
      };
    },
    price: `$${newProduct.price}`,
    action: [
      {
        icon: 'edit',
        textcls: 'success',
        link: '#',
      },
      {
        icon: 'trash-2',
        textcls: 'danger',
        link: '#',
      }
    ],
  })),
};

export default ProductData;
