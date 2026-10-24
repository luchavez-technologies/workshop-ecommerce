import { Head } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { index as ordersIndex } from '@/routes/orders';
import type { Order } from '@/types';

export default function OrdersShow({ order }: { order: Order }) {
    return (
        <>
            <Head title={order.order_number} />

            <div className="flex flex-1 flex-col gap-4 p-4">
                <div>
                    <h1 className="text-xl font-semibold">{order.order_number}</h1>
                    <p className="text-sm text-muted-foreground">
                        Placed {new Date(order.placed_at).toLocaleString()} · Status:{' '}
                        <Badge variant="outline">{order.status}</Badge>
                    </p>
                </div>

                <Card className="max-w-lg">
                    <CardContent className="space-y-3">
                        {order.items.map((item) => (
                            <div key={item.product_id} className="flex justify-between text-sm">
                                <span>
                                    {item.product_name} × {item.quantity}
                                </span>
                                <span>₱{item.line_total.toFixed(2)}</span>
                            </div>
                        ))}

                        <Separator />

                        <div className="flex justify-between text-sm">
                            <span>Subtotal</span>
                            <span>₱{order.subtotal.toFixed(2)}</span>
                        </div>
                        <div className="flex justify-between text-sm">
                            <span>Shipping</span>
                            <span>₱{order.shipping_fee.toFixed(2)}</span>
                        </div>
                        <div className="flex justify-between text-sm">
                            <span>Tax</span>
                            <span>₱{order.tax.toFixed(2)}</span>
                        </div>
                        <Separator />
                        <div className="flex justify-between font-semibold">
                            <span>Total</span>
                            <span>₱{order.total.toFixed(2)}</span>
                        </div>

                        <p className="text-sm text-muted-foreground">
                            Payment: {order.payment.provider} · {order.payment.status}
                        </p>
                    </CardContent>
                </Card>
            </div>
        </>
    );
}

OrdersShow.layout = {
    breadcrumbs: [{ title: 'My orders', href: ordersIndex() }],
};
