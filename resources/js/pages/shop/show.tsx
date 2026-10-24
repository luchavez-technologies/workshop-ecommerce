import { Form, Head } from '@inertiajs/react';
import CartController from '@/actions/App/Http/Controllers/CartController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { index as shopIndex } from '@/routes/shop';
import type { Product } from '@/types';

export default function ShopShow({ product }: { product: Product }) {
    return (
        <>
            <Head title={product.name} />

            <div className="flex flex-1 flex-col gap-6 p-4 md:flex-row">
                <div className="relative aspect-square w-full overflow-hidden rounded-xl border border-sidebar-border/70 md:w-96 dark:border-sidebar-border">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                </div>

                <div className="flex-1 space-y-4">
                    {product.category && <Badge variant="secondary">{product.category.name}</Badge>}

                    <h1 className="text-2xl font-semibold">{product.name}</h1>
                    <p className="text-muted-foreground">{product.description}</p>
                    <p className="text-xl font-semibold">₱{product.price.toFixed(2)}</p>

                    {product.stock_quantity > 0 ? (
                        <p className="text-sm text-muted-foreground">
                            {product.stock_quantity} in stock
                        </p>
                    ) : (
                        <Badge variant="destructive">Out of stock</Badge>
                    )}

                    <Form
                        {...CartController.store.form()}
                        options={{ preserveScroll: true }}
                        className="flex items-end gap-4"
                    >
                        {({ processing }) => (
                            <>
                                <input type="hidden" name="product_id" value={product.id} />

                                <div className="grid gap-2">
                                    <Label htmlFor="quantity">Quantity</Label>
                                    <Input
                                        id="quantity"
                                        name="quantity"
                                        type="number"
                                        min={1}
                                        defaultValue={1}
                                        className="w-24"
                                    />
                                </div>

                                <Button
                                    type="submit"
                                    disabled={processing || product.stock_quantity === 0}
                                >
                                    Add to cart
                                </Button>
                            </>
                        )}
                    </Form>
                </div>
            </div>
        </>
    );
}

ShopShow.layout = {
    breadcrumbs: [{ title: 'Shop', href: shopIndex() }],
};
