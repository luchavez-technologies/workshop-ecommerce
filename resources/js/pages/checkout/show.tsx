import { Form, Head } from '@inertiajs/react';
import CheckoutController from '@/actions/App/Http/Controllers/CheckoutController';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import type { CartItem, Totals } from '@/types';

export default function CheckoutShow({ items, totals }: { items: CartItem[]; totals: Totals }) {
    return (
        <>
            <Head title="Checkout" />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <h1 className="text-xl font-semibold">Checkout</h1>

                <Card className="max-w-lg">
                    <CardContent className="space-y-3">
                        {items.map((item) => (
                            <div key={item.product_id} className="flex justify-between text-sm">
                                <span>
                                    {item.product.name} × {item.quantity}
                                </span>
                                <span>₱{item.line_total.toFixed(2)}</span>
                            </div>
                        ))}

                        <Separator />

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

                        <Form {...CheckoutController.store.form()} className="pt-2">
                            {({ processing }) => (
                                <Button type="submit" disabled={processing} className="w-full">
                                    Pay with Maya
                                </Button>
                            )}
                        </Form>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

CheckoutShow.layout = {
    breadcrumbs: [{ title: 'Checkout', href: '/checkout' }],
};
