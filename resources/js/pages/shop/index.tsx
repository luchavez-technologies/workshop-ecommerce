import { Form, Head, Link } from '@inertiajs/react';
import CartController from '@/actions/App/Http/Controllers/CartController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { index as shopIndex, show as showProduct } from '@/routes/shop';
import type { Category, Product } from '@/types';

export default function ShopIndex({
    products,
    categories,
}: {
    products: Product[];
    categories: Category[];
}) {
    return (
        <>
            <Head title="Shop" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <div>
                    <h1 className="text-xl font-semibold">Shop</h1>
                    <p className="text-sm text-muted-foreground">
                        {categories.length} categories · {products.length} products
                    </p>
                </div>

                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {products.map((product) => (
                        <Card key={product.id}>
                            <CardHeader>
                                <div className="relative mb-2 aspect-video overflow-hidden rounded-lg border border-sidebar-border/70 dark:border-sidebar-border">
                                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                                </div>
                                <CardTitle>
                                    <Link href={showProduct(product.slug)} className="hover:underline">
                                        {product.name}
                                    </Link>
                                </CardTitle>
                                {product.category && (
                                    <Badge variant="secondary">{product.category.name}</Badge>
                                )}
                            </CardHeader>
                            <CardContent className="flex items-center justify-between">
                                <span className="font-semibold">₱{product.price.toFixed(2)}</span>
                                {product.stock_quantity > 0 ? (
                                    <span className="text-xs text-muted-foreground">
                                        {product.stock_quantity} in stock
                                    </span>
                                ) : (
                                    <Badge variant="destructive">Out of stock</Badge>
                                )}
                            </CardContent>
                            <CardFooter>
                                <Form
                                    {...CartController.store.form()}
                                    options={{ preserveScroll: true }}
                                >
                                    {({ processing }) => (
                                        <>
                                            <input type="hidden" name="product_id" value={product.id} />
                                            <Button
                                                type="submit"
                                                size="sm"
                                                disabled={processing || product.stock_quantity === 0}
                                            >
                                                Add to cart
                                            </Button>
                                        </>
                                    )}
                                </Form>
                            </CardFooter>
                        </Card>
                    ))}
                </div>
            </div>
        </>
    );
}

ShopIndex.layout = {
    breadcrumbs: [{ title: 'Shop', href: shopIndex() }],
};
