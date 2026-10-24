import { Form, Head, Link } from '@inertiajs/react';
import CartController from '@/actions/App/Http/Controllers/CartController';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Separator } from '@/components/ui/separator';
import { show as checkoutShow } from '@/routes/checkout';
import { index as shopIndex } from '@/routes/shop';
import type { CartItem, Totals } from '@/types';

export default function CartIndex({ items, totals }: { items: CartItem[]; totals: Totals }) {
    return (
        <>
            <Head title="Cart" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <h1 className="text-xl font-semibold">Your cart</h1>

                {items.length === 0 ? (
                    <Card>
                        <CardContent className="flex flex-col items-start gap-4 py-8">
                            <p className="text-muted-foreground">Your cart is empty.</p>
                            <Button asChild>
                                <Link href={shopIndex()}>Browse the shop</Link>
                            </Button>
                        </CardContent>
                    </Card>
                ) : (
                    <>
                        <div className="space-y-3">
                            {items.map((item) => (
                                <Card key={item.product_id}>
                                    <CardContent className="flex flex-wrap items-center justify-between gap-4">
                                        <div>
                                            <p className="font-medium">{item.product.name}</p>
                                            <p className="text-sm text-muted-foreground">
                                                ₱{item.unit_price.toFixed(2)} each
                                            </p>
                                        </div>

                                        <div className="flex items-center gap-4">
                                            <Form
                                                {...CartController.update.form({
                                                    productId: item.product_id,
                                                })}
                                                options={{ preserveScroll: true }}
                                                className="flex items-center gap-2"
                                            >
                                                {({ processing }) => (
                                                    <>
                                                        <Input
                                                            type="number"
                                                            name="quantity"
                                                            min={0}
                                                            defaultValue={item.quantity}
                                                            className="w-20"
                                                        />
                                                        <Button
                                                            type="submit"
                                                            variant="outline"
                                                            size="sm"
                                                            disabled={processing}
                                                        >
                                                            Update
                                                        </Button>
                                                    </>
                                                )}
                                            </Form>

                                            <span className="w-24 text-right font-medium">
                                                ₱{item.line_total.toFixed(2)}
                                            </span>

                                            <Form
                                                {...CartController.destroy.form({
                                                    productId: item.product_id,
                                                })}
                                                options={{ preserveScroll: true }}
                                            >
                                                {({ processing }) => (
                                                    <Button
                                                        type="submit"
                                                        variant="ghost"
                                                        size="sm"
                                                        disabled={processing}
                                                    >
                                                        Remove
                                                    </Button>
                                                )}
                                            </Form>
                                        </div>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>

                        <Card className="max-w-sm self-end">
                            <CardContent className="space-y-2">
                                <div className="flex justify-between text-sm">
                                    <span>Subtotal</span>
                                    <span>₱{totals.subtotal.toFixed(2)}</span>
                                </div>
                                <div className="flex justify-between text-sm">
                                    <span>Shipping</span>
                                    <span>₱{totals.shipping_fee.toFixed(2)}</span>
                                </div>
                                <div className="flex justify-between text-sm">
                                    <span>Tax</span>
                                    <span>₱{totals.tax.toFixed(2)}</span>
                                </div>
                                <Separator />
                                <div className="flex justify-between font-semibold">
                                    <span>Total</span>
                                    <span>₱{totals.total.toFixed(2)}</span>
                                </div>
                                <Button asChild className="mt-2 w-full">
                                    <Link href={checkoutShow()}>Proceed to checkout</Link>
                                </Button>
                            </CardContent>
                        </Card>
                    </>
                )}
            </div>
        </>
    );
}

CartIndex.layout = {
    breadcrumbs: [{ title: 'Cart', href: '/cart' }],
};
