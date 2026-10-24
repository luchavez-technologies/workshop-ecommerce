import { Head } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import type { Product } from '@/types';

export default function AdminProductsIndex({ products }: { products: Product[] }) {
    return (
        <>
            <Head title="Admin · Products" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <h1 className="text-xl font-semibold">Products</h1>

                <div className="overflow-x-auto rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/50 text-left">
                            <tr>
                                <th className="p-3">Name</th>
                                <th className="p-3">Category</th>
                                <th className="p-3">Price</th>
                                <th className="p-3">Stock</th>
                                <th className="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            {products.map((product) => (
                                <tr key={product.id} className="border-t border-sidebar-border/70 dark:border-sidebar-border">
                                    <td className="p-3 font-medium">{product.name}</td>
                                    <td className="p-3">{product.category?.name ?? '—'}</td>
                                    <td className="p-3">₱{product.price.toFixed(2)}</td>
                                    <td className="p-3">{product.stock_quantity}</td>
                                    <td className="p-3">
                                        <Badge variant={product.is_active ? 'default' : 'secondary'}>
                                            {product.is_active ? 'Active' : 'Inactive'}
                                        </Badge>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}

AdminProductsIndex.layout = {
    breadcrumbs: [{ title: 'Admin · Products', href: '/admin/products' }],
};
